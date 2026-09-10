<template>
    <MasterEMR :isTTD="true" :fieldTTD="'peralihanDPJP'" @simpan="simpan()" @simpanTemplate="simpanTemplate()"
        :ID_PASIEN="ID_PASIEN" :NOREC_PD="NOREC_PD" :norec_emr="norec_emr" v-model:input="input"
        :FORM_NAME="lockedFormName" :FORM_URL="props.FORM_URL" :registrasi="props.registrasi" :pasien="props.pasien"
        :COLLECTION="props.COLLECTION" ref="masterRef" :isLoading="isLoading" @addTemplate="addTemplate">
        <template #content>
            <VCard>
                <div class="columns is-multiline m-0">
                    <div class="column is-3">
                        <h1 style="font-weight: bold">Tanggal MRS</h1>
                        <VDatePicker v-model="input.tanggalMRS" mode="datetime" trim-weeks>
                            <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-3">
                        <h1 style="font-weight: bold">Tanggal KRS</h1>
                        <VDatePicker v-model="input.tanggalKRS" mode="datetime" trim-weeks>
                            <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-3">
                        <h1 style="font-weight: bold">Ruangan</h1>
                        <VControl>
                            <VInput type="text" class="input" placeholder="Ruangan" v-model="input.ruangan" disabled />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <h1 style="font-weight: bold">Cara pembayaran</h1>
                        <VControl>
                            <VInput type="text" class="input" placeholder="Ruangan" v-model="input.Carabayar"
                                disabled />
                        </VControl>
                    </div>
                    <div class="column is-12 pb-0">
                        <h1 style="font-weight: bold">Cara Masuk</h1>
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="IGD" label="IGD (Emergency Unit)"
                                v-model="input.IGD" />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="VK" label="VK (Obstetri Room)"
                                v-model="input.VK" />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="POLIKLINIK"
                                label="POLIKLINIK (Policlinic)" v-model="input.POLIKLINIK" />
                        </VControl>
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square true-value="OK" label="OK (Operation Room)"
                                v-model="input.OK" />
                        </VControl>
                    </div>
                    <div class="column is-12 p-0"></div>
                    <div class="column is-3 p-0"></div>
                    <div class="column is-3">
                        <VButtons class="p-1">
                            <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem_Dokter()"
                                color="info" v-tooltip.bubble="'Tambah Dokter'">
                            </VIconButton>
                            <VIconButton v-if="input.dokterRaber.length > 1" class="mt-1" type="button" raised circle
                                icon="feather:trash" @click="removeItem_Dokter(input.dokterRaber.length - 1)"
                                color="danger">
                            </VIconButton>
                        </VButtons>
                    </div>
                    <div class="column is-12 p-0"></div>
                    <div class="column is-3">
                        <h1 style="font-weight: bold">Dokter DPJP Utama:</h1>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.DpjpUtama" :suggestions="d_Dokter"
                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                        </VControl>
                    </div>
                    <div class="column is-9">
                        <h1 style="font-weight: bold">Dokter Lain Yang Merawat:</h1>
                        <div class="columns is-multiline">
                            <div class="column is-4" v-for="item in input.dokterRaber">
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="item.dokter" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" class="mt-2" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="column is-12 pt-0 pb-0">
                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                </div>

                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Indikasi Rawat Inap</b></span>
                    </div>
                    <div class="column is-3 is-justify-content-left is-align-items-center is-flex pt-0">
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis RI', '', 'IRI')" :disabled="isLoading"
                            :loading="isLoading" color="primary"
                            v-tooltip-prime.top="'Riwayat Asesmen Medis Rawat Inap'">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis Neonatus', '', 'IRI')" :disabled="isLoading"
                            :loading="isLoading" color="info" v-tooltip-prime.top="'Riwayat Asesmen Medis Neonatus'"
                            class="ml-3">
                        </VIconButton>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VTextarea v-model="input.IndikasiRawatInap" rows="4" placeholder="" />
                        </VField>
                    </div>
                </div>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Ringkasan Riwayat Kesehatan :</b></span>
                    </div>
                    <div class="column is-3 is-justify-content-left is-align-items-center is-flex pt-0">
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis RI', '', 'RRK')" :disabled="isLoading"
                            :loading="isLoading" color="primary"
                            v-tooltip-prime.top="'Riwayat Asesmen Medis Rawat Inap'">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis Neonatus', '', 'KELBAYI')" :disabled="isLoading"
                            :loading="isLoading" color="info" v-tooltip-prime.top="'Riwayat Asesmen Medis Neonatus'"
                            class="ml-3">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis IGD', '', 'RRK')" :disabled="isLoading"
                            :loading="isLoading" color="danger"
                            v-tooltip-prime.top="'Riwayat Asesmen Medis Gawat Darurat'" class="ml-3">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('CPPT', '', 'RRK')" :disabled="isLoading" :loading="isLoading"
                            color="warning" v-tooltip-prime.top="'Riwayat CPPT'" class="ml-3">
                        </VIconButton>
                    </div>
                    <div class="column is-12 pt-0">
                        <VField>
                            <VTextarea v-model="input.Anamnesis" rows="4" placeholder="" />
                        </VField>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                        <span><b>Pemeriksaan Fisik :</b></span>
                    </div>
                    <div class="column is-6">
                        <h1 style="font-weight: bold;">Kondisi Umum</h1>
                        <VField>
                            <VControl>
                                <Multiselect v-model="input.kondisiUmum" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_keadaanumum" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off" style="height:100%">
                                </Multiselect>
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3"></div>
                    <div class="column is-3">
                        <h1 style="font-weight: bold;">Tanda-tanda Vital :</h1>
                    </div>
                    <div class="column is-3 is-justify-content-end is-flex">
                        <VIconButton type="button" raised circle icon="feather:book" @click="riwayatVitalSign('PF')"
                            :disabled="isLoading" :loading="isLoadingBill" color="success"
                            v-tooltip-prime.top="'Riwayat Vital Sign'">
                        </VIconButton>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3"></div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">Suhu</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Suhu" v-model="input.celcius" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>°C </VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">Pernafasan</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="RR" v-model="input.nafas" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/menit</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">Berat Badan(BB)</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="BB" v-model="input.BB" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>Kg</VButton>
                            </VControl>
                        </VField>
                    </div>

                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3"></div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">Nadi</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadi" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/menit</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">Tekanan Darah</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Tekanan Darah"
                                    v-model="input.tekananDarah" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>mmHg</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">SpO <sup>2</sup></h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model="input.Spo" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>%</VButton>
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3"></div>
                    <div class="column is-6">
                        <div class="column is-3"></div>
                        <h1 style="font-weight:bold;">GCS</h1>
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <VField addons>
                                    <VControl class="field-addon-body">
                                        <VButton static>E</VButton>
                                    </VControl>
                                    <VControl expanded>
                                        <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E"
                                            label="label" :options="d_gcse" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off"
                                            style="border-radius:0px 4px 4px 0px;height:100%">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField addons>
                                    <VControl class="field-addon-body">
                                        <VButton static>V</VButton>
                                    </VControl>
                                    <VControl expanded>
                                        <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V"
                                            label="label" :options="d_gcsv" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off"
                                            style="border-radius:0px 4px 4px 0px;height:100%">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField addons>
                                    <VControl class="field-addon-body">
                                        <VButton static>M</VButton>
                                    </VControl>
                                    <VControl expanded>
                                        <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M"
                                            label="label" :options="d_gcsm" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off"
                                            style="border-radius:0px 4px 4px 0px;height:100%">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3"></div>
                    <div class="column is-9 px-0 mt-5">
                        <div class="column is-12 py-0">
                            <span><b>Lainnya Yang Bermakna :</b></span>
                        </div>
                        <div class="column is-12">
                            <VIconButton type="button" raised circle icon="feather:file"
                                @click="autoFillEMR('Asesmen Medis RI', '', 'LYB')" :disabled="isLoading"
                                :loading="isLoading" color="success" v-tooltip-prime.top="'Asesmen Medis RI'">
                            </VIconButton>
                        </div>
                        <div class="column is-12 py-0">
                            <VField>
                                <VTextarea v-model="input.Lainnyayangbermakna" rows="4" placeholder="" />
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Pemeriksaan Diagnostik :</b></span>
                        <div class="column is-flex pl-0">
                            <!-- <VIconButton class="mr-2" raised circle icon="fas fa-notes-medical" @click="getLabo()"
                                :loading="isLoading" color="success" v-tooltip-prime.top="'Input Laboratorium'">
                            </VIconButton> -->
                            <!-- <VIconButton raised circle icon="fas fa-user-nurse" @click="getRadio()" :loading="isLoading"
                                color="success" v-tooltip-prime.top="'Input Radiologi'">
                            </VIconButton> -->
                            <VIconButton raised circle icon="fas fa-search" @click="getOrderLab()" :loading="isLoading"
                                color="success" v-tooltip-prime.top="'Input Laboratorium'">
                            </VIconButton>
                            <VIconButton class="ml-2" raised circle icon="fas fa-exclamation-triangle"
                                @click="getOrderRad()" :loading="isLoading" color="success"
                                v-tooltip-prime.top="'Input Radiologi'">
                            </VIconButton>
                            <VIconButton class="ml-2" raised circle icon="fas fa-user-nurse"
                                @click="getPemeriksaanEKG_V2('PemeriksaanDiagnostik_Penunjang')" :loading="isLoading"
                                color="success" v-tooltip-prime.top="'Pemeriksaan EKG'">
                            </VIconButton>
                            <VIconButton class="ml-2" raised circle icon="fas fa-stethoscope"
                                @click="setPenunjang('PemeriksaanDiagnostik_Penunjang')" :loading="isLoading"
                                color="success" v-tooltip-prime.top="'Penunjang Khusus Obgyn'">
                            </VIconButton>
                        </div>
                        <!-- <VField>
                            <VTextarea v-model="input.PemeriksaanDiagnostik" rows="4" placeholder="" />
                        </VField> -->
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <span>Laboratorium</span>
                                <VField>
                                    <VTextarea rows="5" v-model="input.PemeriksaanDiagnostik_Lab"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <span>Radiologi</span>
                                <VField>
                                    <VTextarea rows="5" v-model="input.PemeriksaanDiagnostik_Rad"></VTextarea>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <span>Penunjang</span>
                                <VField>
                                    <VTextarea rows="5" v-model="input.PemeriksaanDiagnostik_Penunjang"></VTextarea>
                                </VField>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Hasil Konsultasi :</b></span>
                    </div>
                    <div class="column is-3 is-justify-content-left is-align-items-center is-flex pt-0">
                        <VIconButton type="button" raised circle icon="feather:book" @click="riwayatCPPT('HK')"
                            :disabled="isLoading" :loading="isLoading" color="success"
                            v-tooltip-prime.top="'Riwayat CPPT'">
                        </VIconButton>
                        <VIconButton raised circle icon="fas fa-user-nurse"
                            @click="getPemeriksaanEKG('Hasilkonsultasi')" :loading="isLoading" color="success"
                            v-tooltip-prime.top="'Pemeriksaan EKG'" class="ml-3">
                        </VIconButton>
                        <VIconButton class="ml-3" raised circle icon="fas fa-stethoscope"
                            @click="setPenunjang('Hasilkonsultasi')" :loading="isLoading" color="success"
                            v-tooltip-prime.top="'Penunjang Khusus Obgyn'">
                        </VIconButton>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VTextarea v-model="input.Hasilkonsultasi" rows="4" placeholder="" />
                        </VField>
                    </div>
                </div>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Diagnosa Utama :</b></span>
                    </div>
                    <div class="column is-3 is-justify-content-left is-align-items-center is-flex pt-0">
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis RI', 'DU', 'Diagnosa')" :disabled="isLoading"
                            :loading="isLoading" color="primary"
                            v-tooltip-prime.top="'Riwayat Asesmen Medis Rawat Inap'">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis Neonatus', 'DU', 'Diagnosa')" :disabled="isLoading"
                            :loading="isLoading" color="info" v-tooltip-prime.top="'Riwayat Asesmen Medis Neonatus'"
                            class="ml-3">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis IGD', 'DU', 'Diagnosa')" :disabled="isLoading"
                            :loading="isLoading" color="danger"
                            v-tooltip-prime.top="'Riwayat Asesmen Medis Gawat Darurat'" class="ml-3">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('CPPT', 'DU', 'Diagnosa')" :disabled="isLoading" :loading="isLoading"
                            color="warning" v-tooltip-prime.top="'Riwayat CPPT'" class="ml-3">
                        </VIconButton>
                    </div>
                    <!-- <div class="column is-flex">
                            <VIconButton class="mr-2" raised circle icon="fas fa-notes-medical" @click="getLabo2()"
                                :loading="isLoading" color="success" v-tooltip-prime.top="'Input Laboratorium'">
                            </VIconButton>
                            <VIconButton raised circle icon="fas fa-user-nurse" @click="getRadio2()"
                                :loading="isLoading" color="success" v-tooltip-prime.top="'Input Radiologi'">
                            </VIconButton>
                            <VIconButton class="ml-2" raised circle icon="fas fa-user-nurse"
                                @click="getPemeriksaanEKG3()" :loading="isLoading" color="success"
                                v-tooltip-prime.top="'Pemeriksaan EKG'">
                            </VIconButton>
                        </div> -->
                    <div class="column is-12">
                        <VField>
                            <VTextarea v-model="input.DiagnosaUtama" rows="4" placeholder="" />
                        </VField>
                    </div>
                </div>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Diagnosa Sekunder :</b></span>
                    </div>
                    <div class="column is-3 is-justify-content-left is-align-items-center is-flex pt-0">
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis RI', 'DS', 'Diagnosa')" :disabled="isLoading"
                            :loading="isLoading" color="primary"
                            v-tooltip-prime.top="'Riwayat Asesmen Medis Rawat Inap'">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis Neonatus', 'DS', 'Diagnosa')" :disabled="isLoading"
                            :loading="isLoading" color="info" v-tooltip-prime.top="'Riwayat Asesmen Medis Neonatus'"
                            class="ml-3">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis IGD', 'DS', 'Diagnosa')" :disabled="isLoading"
                            :loading="isLoading" color="danger"
                            v-tooltip-prime.top="'Riwayat Asesmen Medis Gawat Darurat'" class="ml-3">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('CPPT', 'DS', 'Diagnosa')" :disabled="isLoading" :loading="isLoading"
                            color="warning" v-tooltip-prime.top="'Riwayat CPPT'" class="ml-3">
                        </VIconButton>
                    </div>
                    <!-- <div class="column is-flex">
                            <VIconButton class="mr-2" raised circle icon="fas fa-notes-medical" @click="getLabo3()"
                                :loading="isLoading" color="success" v-tooltip-prime.top="'Input Laboratorium'">
                            </VIconButton>
                            <VIconButton raised circle icon="fas fa-user-nurse" @click="getRadio3()"
                                :loading="isLoading" color="success" v-tooltip-prime.top="'Input Radiologi'">
                            </VIconButton>
                            <VIconButton class="ml-2" raised circle icon="fas fa-user-nurse"
                                @click="getPemeriksaanEKG4()" :loading="isLoading" color="success"
                                v-tooltip-prime.top="'Pemeriksaan EKG'">
                            </VIconButton>
                        </div> -->
                    <div class="column is-12">
                        <VField>
                            <VTextarea v-model="input.DiagnosaSekunder" rows="4" />
                        </VField>
                    </div>
                </div>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Komorbiditas :</b></span>
                    </div>
                    <div class="column is-3 is-justify-content-left is-align-items-center is-flex pt-0">
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis RI', 'Komorbiditas', 'Diagnosa')" :disabled="isLoading"
                            :loading="isLoading" color="primary"
                            v-tooltip-prime.top="'Riwayat Asesmen Medis Rawat Inap'">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis Neonatus', 'Komorbiditas', 'Diagnosa')"
                            :disabled="isLoading" :loading="isLoading" color="info"
                            v-tooltip-prime.top="'Riwayat Asesmen Medis Neonatus'" class="ml-3">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis IGD', 'Komorbiditas', 'Diagnosa')" :disabled="isLoading"
                            :loading="isLoading" color="danger"
                            v-tooltip-prime.top="'Riwayat Asesmen Medis Gawat Darurat'" class="ml-3">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('CPPT', 'Komorbiditas', 'Diagnosa')" :disabled="isLoading"
                            :loading="isLoading" color="warning" v-tooltip-prime.top="'Riwayat CPPT'" class="ml-3">
                        </VIconButton>
                    </div>
                    <!-- <div class="column is-flex">
                            <VIconButton class="mr-2" raised circle icon="fas fa-notes-medical" @click="getLabo3()"
                                :loading="isLoading" color="success" v-tooltip-prime.top="'Input Laboratorium'">
                            </VIconButton>
                            <VIconButton raised circle icon="fas fa-user-nurse" @click="getRadio3()"
                                :loading="isLoading" color="success" v-tooltip-prime.top="'Input Radiologi'">
                            </VIconButton>
                            <VIconButton class="ml-2" raised circle icon="fas fa-user-nurse"
                                @click="getPemeriksaanEKG4()" :loading="isLoading" color="success"
                                v-tooltip-prime.top="'Pemeriksaan EKG'">
                            </VIconButton>
                        </div> -->
                    <div class="column is-12">
                        <VField>
                            <VTextarea v-model="input.Komorbiditas" rows="4" />
                        </VField>
                    </div>
                </div>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Prosedur Terapi Yang Telah Dikerjakan :</b></span>
                    </div>
                    <div class="column is-3 is-justify-content-left is-align-items-center is-flex pt-0">
                        <!-- <VIconButton raised circle icon="fas fa-user-nurse" @click="getTindakan()" :loading="isLoadingTindakan"
                            color="success" v-tooltip-prime.top="'Tindakan'">
                        </VIconButton> -->
                        <VIconButton type="button" raised circle icon="feather:book" @click="riwayatCPPT('PTYTD')"
                            :disabled="isLoading" :loading="isLoading" color="success"
                            v-tooltip-prime.top="'Riwayat CPPT'">
                        </VIconButton>
                        <VIconButton raised circle icon="fas fa-user-nurse" @click="getListTindakan()"
                            :loading="isLoadingTindakan1" color="success" v-tooltip-prime.top="'Obat'" class="ml-3">
                        </VIconButton>
                        <VIconButton raised circle icon="fas fa-user-nurse" @click="getListTindakan100()"
                            :loading="isloadingTindakan100" color="success" v-tooltip-prime.top="'Tindakan'"
                            class="ml-3">
                        </VIconButton>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VTextarea v-model="input.ProsedurTerapi" rows="4" placeholder="" />
                        </VField>
                    </div>
                </div>
                <Dialog v-model:visible="modalTindakan1" modal header="Obat" :style="{ width: '100rem' }"
                    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
                    <div class="columns is-multiline mt-5" v-if="isLoadingTindakan1">
                        <VPlaceloadText :lines="1" class="p-2" />
                        <div class="column is-12" v-for="key in 2" :key="key">
                            <VPlaceloadWrap>
                                <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                            </VPlaceloadWrap>
                        </div>
                    </div>
                    <div v-else>
                        <VField>
                            <VInput v-model="cariObat" placeholder="Cari Obat" />
                        </VField>
                        <table class="table-pri">
                            <thead>
                                <th class="th-pri">
                                    <span>No</span>
                                </th>
                                <th class="th-pri">
                                    <span>Obat Yang Telah Diberikan</span>
                                </th>
                                <th class="th-pri">
                                    <span>Aksi</span>
                                </th>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in filteredTindakan" :key="item.id">
                                    <td class="td-pri">
                                        <span>{{ index + 1 }}</span>
                                    </td>
                                    <td class="td-pri">
                                        <span>{{ item.namaproduk }}</span>
                                    </td>
                                    <td class="td-pri" style="text-align: center;">
                                        <VIconButton raised circle icon="fas fa-plus"
                                            @click="addTindakan(item.namaproduk)" :loading="isLoadingTindakan1"
                                            color="info" v-tooltip-prime.top="'Tindakan'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </Dialog>
                <Dialog v-model:visible="modalTindakan100" modal header="Tindakan" :style="{ width: '100rem' }"
                    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
                    <div class="columns is-multiline mt-5" v-if="isloadingTindakan100">
                        <VPlaceloadText :lines="1" class="p-2" />
                        <div class="column is-12" v-for="key in 2" :key="key">
                            <VPlaceloadWrap>
                                <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                            </VPlaceloadWrap>
                        </div>
                    </div>
                    <div v-else>
                        <!-- <pre>{{ listTindakan1 }}</pre> -->
                        <VField>
                            <VInput v-model="cariTindakan100" placeholder="Cari Tindakan" />
                        </VField>
                        <table class="table-pri">
                            <thead>
                                <th class="th-pri">
                                    <span>No</span>
                                </th>
                                <th class="th-pri">
                                    <span>Tindakan yang Telah Dilakukan</span>
                                </th>
                                <th class="th-pri">
                                    <span>Aksi</span>
                                </th>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in filteredTindakan100" :key="item.id">
                                    <td class="td-pri">
                                        <span>{{ index + 1 }}</span>
                                    </td>
                                    <td class="td-pri">
                                        <span>{{ item.namaproduk }}</span>
                                    </td>
                                    <td class="td-pri">
                                        <VIconButton raised circle icon="fas fa-plus"
                                            @click="addTindakan100(item.namaproduk)" :loading="isLoadingTindakan1"
                                            color="info" v-tooltip-prime.top="'Tindakan'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </Dialog>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Tindakan Yang Telah Dikerjakan :</b></span><br>
                    </div>
                    <div class="column is-3 is-justify-content-left is-align-items-center is-flex pt-0">
                        <VIconButton type="button" raised circle icon="feather:book"
                            @click="autoFillEMR('CPPT', '', 'TindakanDikerjakan')" :disabled="isLoading"
                            :loading="isLoading" color="success" v-tooltip-prime.top="'Riwayat CPPT'">
                        </VIconButton>
                        <VIconButton raised circle icon="fas fa-user-nurse" @click="getListTindakan2()"
                            :loading="isLoadingTindakan1" color="success" v-tooltip-prime.top="'Tindakan'" class="ml-3">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:printer" @click="cetakLaporanOperasi()"
                            :disabled="isLoading" :loading="isLoading" color="warning"
                            v-tooltip-prime.top="'Perview Laporan Operasi'" class="ml-3">
                        </VIconButton>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VTextarea v-model="input.TindakanDikerjakan" rows="4" placeholder="" />
                        </VField>
                    </div>
                </div>
                <Dialog v-model:visible="modalTindakan2" modal header="Tindakan" :style="{ width: '100rem' }"
                    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
                    <div class="columns is-multiline mt-5" v-if="isLoadingTindakan2">
                        <VPlaceloadText :lines="1" class="p-2" />
                        <div class="column is-12" v-for="key in 2" :key="key">
                            <VPlaceloadWrap>
                                <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                                <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                            </VPlaceloadWrap>
                        </div>
                    </div>
                    <div v-else>
                        <!-- <pre>{{ listTindakan1 }}</pre> -->
                        <VField>
                            <VInput v-model="cariTindakan2" placeholder="Cari Tindakan" />
                        </VField>
                        <table class="table-pri">
                            <thead>
                                <th class="th-pri">
                                    <span>No</span>
                                </th>
                                <th class="th-pri">
                                    <span>Tindakan yang Telah Dilakukan</span>
                                </th>
                                <th class="th-pri">
                                    <span>Aksi</span>
                                </th>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in filteredTindakan2" :key="item.id">
                                    <td class="td-pri">
                                        <span>{{ index + 1 }}</span>
                                    </td>
                                    <td class="td-pri">
                                        <span>{{ item.namaproduk }}</span>
                                    </td>
                                    <td class="td-pri" style="text-align: center;">
                                        <VIconButton raised circle icon="fas fa-plus"
                                            @click="addTindakan2(item.namaproduk)" :loading="isLoadingTindakan1"
                                            color="info" v-tooltip-prime.top="'Tindakan'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </Dialog>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Alergi (Reaksi Obat) :</b></span>
                        <div class="column is-12 is-justify-content-left is-align-items-center is-flex pt-0">
                            <VIconButton type="button" raised circle icon="feather:file-text"
                                @click="riwayat_Alergi('Asesmen Medis RI')" :disabled="isLoading" :loading="isLoading"
                                color="primary" v-tooltip-prime.top="'Riwayat Asesmen Medis Rawat Inap'">
                            </VIconButton>
                            <VIconButton type="button" raised circle icon="feather:file-text"
                                @click="riwayat_Alergi('Asesmen Medis Neonatus')" :disabled="isLoading"
                                :loading="isLoading" color="info" v-tooltip-prime.top="'Riwayat Asesmen Medis Neonatus'"
                                class="ml-3">
                            </VIconButton>
                        </div>
                        <VField>
                            <VTextarea v-model="input.AlergiReaksiObat" rows="4" placeholder="" />
                        </VField>
                    </div>
                </div>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Diet Yang Telah Diberikan & Diet Yang Harus Dilakukan di Rumah Diet :</b></span>
                        <div class="column is-12 is-justify-content-left is-align-items-center is-flex pt-0">
                            <VIconButton type="button" raised circle icon="feather:file-text"
                                @click="riwayat_Diet('Asesmen Medis RI')" :disabled="isLoading" :loading="isLoading"
                                color="primary" v-tooltip-prime.top="'Riwayat Asesmen Medis Rawat Inap'">
                            </VIconButton>
                            <VIconButton type="button" raised circle icon="feather:file-text"
                                @click="riwayat_Diet('Asesmen Medis Neonatus')" :disabled="isLoading"
                                :loading="isLoading" color="info" v-tooltip-prime.top="'Riwayat Asesmen Medis Neonatus'"
                                class="ml-3">
                            </VIconButton>
                        </div>
                        <VField>
                            <VTextarea v-model="input.Dietdiberikan" rows="4" placeholder="" />
                        </VField>
                    </div>
                </div>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Instruksi Tindak Lanjut :</b></span>
                    </div>
                    <div class="column is-3 is-justify-content-left is-align-items-center is-flex pt-0">
                        <VIconButton type="button" raised circle icon="feather:book" @click="riwayatCPPT('ITL')"
                            :disabled="isLoading" :loading="isLoading" color="success"
                            v-tooltip-prime.top="'Riwayat CPPT'">
                        </VIconButton>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VTextarea v-model="input.InstruksiTindakLanjut" rows="4" placeholder="" />
                        </VField>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                        <span><b>Kondisi dan Tanda-tanda Vital Pasien Waktu Keluar RS :</b></span>
                    </div>
                    <div class="column is-6">
                        <h1 style="font-weight: bold;">Kondisi Umum</h1>
                        <VField>
                            <VControl>
                                <Multiselect v-model="input.kondisiUmumKeluar" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_keadaanumum" :searchable="true"
                                    track-by="label" mode="single" autocomplete="off" style="height:100%">
                                </Multiselect>
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3"></div>
                    <div class="column is-3">
                        <h1 style="font-weight: bold;">Tanda-tanda Vital :</h1>
                    </div>
                    <div class="column is-3 is-justify-content-end is-flex">
                        <VIconButton type="button" raised circle icon="feather:book"
                            @click="riwayatVitalSign('TTV_Keluar_RS')" :disabled="isLoading" :loading="isLoadingBill"
                            color="success" v-tooltip-prime.top="'Riwayat Vital Sign'">
                        </VIconButton>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3"></div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">Suhu</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Suhu" v-model="input.celciusKeluar" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>°C </VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">Pernafasan</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="RR" v-model="input.nafasKeluar" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/menit</VButton>
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3"></div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">Nadi</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadiKeluar" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/menit</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pt-0">
                        <h1 style="font-weight: bold;">Tekanan Darah</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Tekanan Darah"
                                    v-model="input.tekananDarahKeluar" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>mmHg</VButton>
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                        <span><b>Status Pasien Waktu Keluar RS:</b></span>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Sembuh"
                                    label="Sembuh" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar"
                                    true-value="Meninggal ≤ 48 Jam" label="Meninggal ≤ 48 Jam" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Dirujuk Ke :"
                                    label="Dirujuk Ke" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBDirujukke" />
                        </VControl>
                    </div>
                </div>

                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Membaik"
                                    label="Membaik" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar"
                                    true-value="Meninggal ≥ 48 Jam" label="Meninggal ≥ 48 Jam" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VControl>
                                <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar"
                                    true-value="Pulang Atas Permintaan Sendiri" label="Pulang Atas Permintaan Sendiri"
                                    color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VCheckbox class="fontcheckbox" v-model="input.riwayatkeluar" true-value="Lain-lain"
                                    label="Lain-lain" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2" style="margin-left: -50px;">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBLainlain" />
                        </VControl>
                    </div>
                </div>
                <div class="columns is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-12 mt-5">
                        <span><b>Penyebab Kematian :</b></span>
                        <VField>
                            <VTextarea v-model="input.PenyebabKematian" rows="4" placeholder="" />
                        </VField>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                        <span><b>Pengobatan Dilanjutkan:</b></span>
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="PoliklinikRSUDBaliMandara"
                                label="Poliklinik RSUD Bali Mandara" v-model="input.PoliklinikRSUDBaliMandara" />
                        </VControl>
                    </div>
                    <div class="column is-2"></div>
                    <div class="column is-2">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="Faskes1" label="Faskes 1"
                                v-model="input.Faskes1" />
                        </VControl>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                    </div>
                    <div class="column is-9">
                        <div class="columns is-multiline">
                            <div class="column is-6 is-flex">
                                <div class="column is-4 mt-2">
                                    <span>Poliklinik Tujuan :</span>
                                </div>
                                <div class="column is-8">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.PoliTujuan" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="column is-6 is-flex">
                                <div class="column is-4 mt-2">
                                    <span>Nama Faskes 1 :</span>
                                </div>
                                <div class="column is-8">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.NamaFaskes1" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                    </div>
                    <div class="column is-3" style="margin-top: 10px;">
                        <span>Tanggal Kontrol :</span>
                    </div>
                    <div class="column is-2" style="margin-left: -90px;">
                        <VField>
                            <VDatePicker v-model="input.tanggalKontrol" mode="date" style="width: 100%;" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField style="margin-bottom: 0.70rem;">
                                        <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                    </div>
                    <div class="column is-3">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="RSLain" label="RS Lain"
                                v-model="input.RSLain" />
                        </VControl>
                    </div>
                    <div class="column is-2"></div>
                    <div class="column is-2">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="PengobatanLainlain"
                                label="Lain-Lain:" v-model="input.PengobatanLainlain" />
                        </VControl>
                    </div>
                    <div class="column is-2" style="margin-top: -10px;margin-left: -50px;">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBPengobatanLainlain" />
                        </VControl>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                    </div>
                    <div class="column is-3" style="margin-top: 10px;">
                        <span>Nama RS :</span>
                    </div>
                    <div class="column is-2" style="margin-left: -90px;">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.NamaRS" />
                        </VControl>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                    </div>
                    <div class="column is-3">
                        <span>Pasien Bisa Berpergian :</span>
                    </div>
                    <div class="column is-3" style="margin-left: -50px;">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="TanpaPendamping"
                                label="Tanpa Pendamping" v-model="input.TanpaPendamping" />
                        </VControl>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                    </div>
                    <div class="column is-3">
                    </div>
                    <div class="column is-3" style="margin-left: -50px;">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="DenganPendampingMedis"
                                label="Dengan Pendamping Medis" v-model="input.DenganPendampingMedis" />
                        </VControl>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                    </div>
                    <div class="column is-3">
                        <span>Pasien Memerlukan :</span>
                    </div>
                    <div class="column is-3" style="margin-left: -50px;">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="TempatDudukBiasa"
                                label="Tempat Duduk Biasa" v-model="input.TempatDudukBiasa" />
                        </VControl>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                    </div>
                    <div class="column is-3">
                    </div>
                    <div class="column is-3" style="margin-left: -50px;">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="KursiRoda" label="Kursi Roda"
                                v-model="input.KursiRoda" />
                        </VControl>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <div class="column is-3">
                    </div>
                    <div class="column is-3">
                    </div>
                    <div class="column is-3" style="margin-left: -50px;">
                        <VControl raw subcontrol>
                            <VCheckbox class="p-0" color="primary" square :true-value="TidakMemerlukan"
                                label="Tidak Memerlukan" v-model="input.TidakMemerlukan" />
                        </VControl>
                    </div>
                </div>
                <div class="column is-12 columns is-multiline pb-0 mb-0">
                    <table border="1">
                        <tr>
                            <td colspan="5" style="background-color: skyblue;">
                                <div class="columns is-multiline m-0">
                                    <div class="column is-6 is-flex is-align-items-center">
                                        <span>Obat Selama Rawat Inap</span>
                                    </div>
                                    <div class="column is-6 is-flex is-align-items-center is-justify-content-end">
                                        <VIconButton type="button" raised circle icon="lnir lnir-medicine-alt"
                                            @click="riwayatObatPasien(1)" :disabled="isLoading" :loading="isLoading"
                                            color="link" v-tooltip-prime.top="'Riwayat Obat Pasien'">
                                        </VIconButton>
                                    </div>
                                </div>
                            </td>
                            <td colspan="5" style="background-color: skyblue;">
                                <div class="columns is-multiline m-0">
                                    <div class="column is-6 is-flex is-align-items-center">
                                        <span>Obat Setelah Rawat Inap</span>
                                    </div>
                                    <div class="column is-6 is-flex is-align-items-center is-justify-content-end">
                                        <VIconButton type="button" raised circle icon="lnir lnir-medicine-alt"
                                            @click="riwayatObatPasien(2)" :disabled="isLoading" :loading="isLoading"
                                            color="warning" v-tooltip-prime.top="'Riwayat Obat Pasien'">
                                        </VIconButton>
                                    </div>
                                </div>
                            </td>
                            <td rowspan="2"
                                style="background-color: skyblue;text-align: center;vertical-align: middle;">
                                <span>#</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: skyblue;">
                                <div class="column is-12"><span>Nama Obat</span></div>
                            </td>
                            <td style="background-color: skyblue;">
                                <div class="column is-12"><span>Jml</span></div>
                            </td>
                            <td style="background-color: skyblue;">
                                <div class="column is-12"><span>Dosis</span></div>
                            </td>
                            <td style="background-color: skyblue;">
                                <div class="column is-12"><span>Frekuensi</span></div>
                            </td>
                            <td style="background-color: skyblue;">
                                <div class="column is-12"><span>Cara Pemberian</span></div>
                            </td>
                            <td style="background-color: skyblue;">
                                <div class="column is-12"><span>Nama Obat</span></div>
                            </td>
                            <td style="background-color: skyblue;">
                                <div class="column is-12"><span>Jml</span></div>
                            </td>
                            <td style="background-color: skyblue;">
                                <div class="column is-12"><span>Dosis</span></div>
                            </td>
                            <td style="background-color: skyblue;">
                                <div class="column is-12"><span>Frekuensi</span></div>
                            </td>
                            <td style="background-color: skyblue;">
                                <div class="column is-12"><span>Cara Pemberian</span></div>
                            </td>
                        </tr>
                        <tr v-for="(item, index) in input.details" :key="index">
                            <td>
                                <VField>
                                    <VTextarea rows="2" v-model="item.NamaobatSelama"></VTextarea>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VTextarea rows="2" v-model="item.JmlSelama"></VTextarea>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VTextarea rows="2" v-model="item.DosisSelama"></VTextarea>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VTextarea rows="2" v-model="item.FrekuensiSelama"></VTextarea>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VTextarea rows="2" v-model="item.CarapemberianSelama"></VTextarea>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VTextarea rows="2" v-model="item.NamaobatSetelah"></VTextarea>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VTextarea rows="2" v-model="item.JmlSetelah"></VTextarea>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VTextarea rows="2" v-model="item.DosisSetelah"></VTextarea>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VTextarea rows="2" v-model="item.FrekuensiSetelah"></VTextarea>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VTextarea rows="2" v-model="item.CarapemberianSetelah"></VTextarea>
                                </VField>
                            </td>
                            <td>
                                <VButtons class="p-1 is-flex is-justify-content-center	">
                                    <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()"
                                        color="info" v-tooltip.bubble="'Tambah'">
                                    </VIconButton>
                                    <VIconButton v-if="input.details.length > 1 && index != 0" class="mt-1"
                                        type="button" raised circle icon="feather:trash" @click="removeItem(index)"
                                        color="danger">
                                    </VIconButton>
                                </VButtons>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="column is-12">
                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                </div>

                <div class="columns is-multiline m-0">
                    <div class="column is-8"></div>
                    <div class="column is-4">
                        <VField label="Garut">
                            <VDatePicker v-model="input.tanggalPengisian" mode="datetime" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <div class="column" style="text-align:center;">
                            <h1>Pasien / Keluarga Pasien</h1>
                            <TandaTangan :elemenID="'TTDpasien'" :width="'150'" :height="'150'" class="dek" />
                            <VControl>
                                <VInput type="text" class="input" v-model="input.Pasienkeluarga" />
                            </VControl>
                        </div>
                    </div>
                    <div class="column is-4"></div>
                    <div class="column is-4">
                        <div class="column" style="text-align:center;">
                            <h1>Dokter Penanggung Jawab Pelayanan</h1>
                            <!-- <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" /> -->
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.DokterPenanggungJawab" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                </div>

            </VCard>
        </template>
    </MasterEMR>

    <VModal :open="showModalVitalSign" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalVitalSign = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listVitalSign.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                    <td class="tg-0lax text-center" width="5%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="5%">Suhu</td>
                                    <td class="tg-0lax text-center" width="5%">Nadi</td>
                                    <td class="tg-0lax text-center" width="5%">Tekanan Darah</td>
                                    <td class="tg-0lax text-center" width="5%">Pernafasan</td>
                                    <td class="tg-0lax text-center" width="5%">SPO2</td>
                                </tr>
                            </thead>
                            <tbody v-for="response in listVitalSign">
                                <tr>
                                    <td style="width:5%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addRiwayatVitalSign(response)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <VDatePicker class="pt-0 pb-0 pl-0" v-model="response.tanggal" color="green"
                                            trim-weeks mode="dateTime">
                                            <template #default="{ inputValue, inputEvents }" class="pb-0">
                                                <VField>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date"
                                                            :value="inputValue" v-on="inputEvents" class="is-rounded"
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <span class="mb-2">{{ response.suhu }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <span class="mb-2">{{ response.nadi }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <span class="mb-2">{{ response.tekananDarah }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <span class="mb-2">{{ response.pernapasan }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <span class="mb-2">{{ response.SPO2 }}</span><br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal>

    <VModal :open="showModalCPPT" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalCPPT = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listCPPT.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                    <td class="tg-0lax text-center" width="15%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="20%">S</td>
                                    <td class="tg-0lax text-center" width="20%">O</td>
                                    <td class="tg-0lax text-center" width="20%">A</td>
                                    <td class="tg-0lax text-center" width="20%">P</td>
                                </tr>
                            </thead>
                            <tbody v-for="response in listCPPT">
                                <tr>
                                    <td style="width:5%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addRiwayatCPPT_All(response)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <VDatePicker class="pt-0 pb-0 pl-0" v-model="response.tgl" color="green"
                                            trim-weeks mode="dateTime">
                                            <template #default="{ inputValue, inputEvents }" class="pb-0">
                                                <VField>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date"
                                                            :value="inputValue" v-on="inputEvents" class="is-rounded"
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <span class="mb-2">{{ response.S }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <span class="mb-2">{{ response.O }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <span class="mb-2">{{ response.A }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <span class="mb-2">{{ response.P }}</span><br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal>

    <VModal :open="showModalRiwayatCPPT" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalRiwayatCPPT = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="table-tg" v-if="listRiwayatCPPT.length > 0" style="width: 100%;">
                            <thead>
                                <tr>
                                    <td class="border center" width="10%">#</td>
                                    <td class="border center" width="30%">Tanggal</td>
                                    <td class="border center" width="15%">Subjective</td>
                                    <td class="border center" width="15%">Objective</td>
                                    <td class="border center" width="15%">Assesments</td>
                                    <td class="border center" width="15%">Planning</td>
                                </tr>
                            </thead>
                            <tbody v-for="response in listRiwayatCPPT">
                                <tr>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addRiwayatCPPT(response)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VDatePicker class="pt-0 pb-0 pl-0" v-model="response.tgl" color="green"
                                            trim-weeks mode="dateTime">
                                            <template #default="{ inputValue, inputEvents }" class="pb-0">
                                                <VField>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date"
                                                            :value="inputValue" v-on="inputEvents" class="is-rounded"
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <span class="mb-2">{{ response.S }}</span>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <span class="mb-2">{{ response.O }}</span>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <span class="mb-2">{{ response.A }}</span>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <span class="mb-2">{{ response.P }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal>

    <VModal :open="showModalAsmedRI" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalAsmedRI = false">
        <template #content>
            <form class="modal-form" v-if="switchRiwayat == 'Diagnosa'">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="table-tg" v-if="listAsmedRI.length > 0" style="width: 100%;">
                            <thead>
                                <tr>
                                    <td class="border center" width="10%">#</td>
                                    <td class="border center" width="30%">Tanggal</td>
                                    <td class="border center" width="60%">Diagnosa</td>
                                </tr>
                            </thead>
                            <tbody v-for="response in listAsmedRI">
                                <tr>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addRiwayatAsmedRI(response.TADiagnosa)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VDatePicker class="pt-0 pb-0 pl-0" v-model="response.created_at" color="green"
                                            trim-weeks mode="dateTime">
                                            <template #default="{ inputValue, inputEvents }" class="pb-0">
                                                <VField>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date"
                                                            :value="inputValue" v-on="inputEvents" class="is-rounded"
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <span class="mb-2">{{ response.TADiagnosa }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <form class="modal-form" v-else-if="switchRiwayat == 'RRK'">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="table-tg" v-if="listAsmedRI.length > 0" style="width: 100%;">
                            <thead>
                                <tr>
                                    <td class="border center" width="10%">#</td>
                                    <td class="border center" width="30%">Tanggal</td>
                                    <td class="border center" width="30%">Alloanamnesis</td>
                                    <td class="border center" width="30%">Anamnesis</td>
                                </tr>
                            </thead>
                            <tbody v-for="response in listAsmedRI">
                                <tr>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addRiwayatAsmedRI(response)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VDatePicker class="pt-0 pb-0 pl-0" v-model="response.created_at" color="green"
                                            trim-weeks mode="dateTime">
                                            <template #default="{ inputValue, inputEvents }" class="pb-0">
                                                <VField>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date"
                                                            :value="inputValue" v-on="inputEvents" class="is-rounded"
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <span class="mb-2">{{ response.kebpilihanallo }}</span>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <span class="mb-2">{{ response.anamnesis }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <form class="modal-form" v-else-if="switchRiwayat == 'IRI'">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="table-tg" v-if="listAsmedRI.length > 0" style="width: 100%;">
                            <thead>
                                <tr>
                                    <td class="border center" width="10%">#</td>
                                    <td class="border center" width="30%">Tanggal</td>
                                    <td class="border center" width="60%">Indikasi Rawat Inap</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="response in listAsmedRI">
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addRiwayatAsmedRI(response.indikasi)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VDatePicker class="pt-0 pb-0 pl-0" v-model="response.created_at" color="green"
                                            trim-weeks mode="dateTime">
                                            <template #default="{ inputValue, inputEvents }" class="pb-0">
                                                <VField>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date"
                                                            :value="inputValue" v-on="inputEvents" class="is-rounded"
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <span class="mb-2">{{ response.indikasi }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <form class="modal-form" v-else-if="switchRiwayat == 'LYB'">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="table-tg" v-if="listAsmedRI.length > 0" style="width: 100%;">
                            <thead>
                                <tr>
                                    <td class="border center" width="10%">#</td>
                                    <td class="border center" width="30%">Tanggal</td>
                                    <td class="border center" width="60%">Section</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="response in listAsmedRI">
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addRiwayatAsmedRI(response)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VDatePicker class="pt-0 pb-0 pl-0" v-model="response.created_at" color="green"
                                            trim-weeks mode="dateTime">
                                            <template #default="{ inputValue, inputEvents }" class="pb-0">
                                                <VField>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Select a date"
                                                            :value="inputValue" v-on="inputEvents" class="is-rounded"
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </td>
                                    <td class="border" style="text-align:center;padding:5px;vertical-align: middle;">
                                        <span class="mb-2">{{ response.section_SL ? response.section_SL.label : '-'
                                        }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal>

    <VModal :open="showModalRiwayatObatPasien" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalRiwayatObatPasien = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat Obat Pasien</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <VField>
                            <VInput placeholder="Cari Obat" v-model="inputObatPasien" />
                        </VField>
                        <table class="tg table-tg" v-if="listObatPasien.length > 0" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="border text-center" width="10%">#</th>
                                    <th class="border text-center" width="">Nama Obat</th>
                                    <th class="border text-center" width="">Jumlah</th>
                                    <th class="border text-center" width="">Dosis</th>
                                    <th class="border text-center" width="">Frekuensi</th>
                                    <th class="border text-center" width="">Cara Pemberian</th>
                                    <th class="border text-center" width="">Action</th>
                                </tr>
                            </thead>
                            <tbody v-for="(response, index) in listRiwayatObatPasien" :index="index">
                                <tr>
                                    <td class="border center">{{ index }}</td>
                                    <td class="border pd" style="vertical-align: middle;">{{
                                        response.details[0].namaproduk }}</td>
                                    <td class="border center">{{ response.details[0].jumlah ?? '-' }}</td>
                                    <td class="border center">{{ response.details[0].dosis ?? '-' }}</td>
                                    <td class="border center">-</td>
                                    <td class="border center">{{ response.details[0].aturanpakai ?? '-' }}</td>
                                    <td class="border center pd">
                                        <VButtons class="is-flex is-justify-content-center">
                                            <VIconButton type="button" circle icon="feather:plus"
                                                @click="addObat(response.details[0])" color="info">
                                            </VIconButton>
                                        </VButtons>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal>

    <VModal :open="showModalOrderLab" title="Riwayat Lab" :noclose="true" size="large" actions="right"
        @close="showModalOrderLab = false">
        <template #content>
            <form class="modal-form">
                <div class="columns is-multiline column is-12 py-0">
                    <div class="column is-3" style="text-align: center;font-weight: bold;">
                        <VTag color="danger">Total Bank Darah : {{ totalLabCounts.bank_darah }}</VTag>
                    </div>
                    <div class="column is-3" style="text-align: center;font-weight: bold;">
                        <VTag color="info">Total Lab PK : {{ totalLabCounts.lab_PK }}</VTag>
                    </div>
                    <div class="column is-3" style="text-align: center;font-weight: bold;">
                        <VTag color="link">Total Lab PA : {{ totalLabCounts.lab_PA }}</VTag>
                    </div>
                    <div class="column is-3" style="text-align: center;font-weight: bold;">
                        <VTag color="primary">Total Lab Mikro : {{ totalLabCounts.lab_Mikro }}</VTag>
                    </div>
                </div>
                <div class="column is-12 pt-0 pb-0">
                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                </div>
                <div class="columns is-multiline column is-12 m-0 p-0">
                    <div class="column is-3">
                        <span>Lab</span>
                        <Multiselect v-model="item.ruangan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                            :options="d_Ruangan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                    </div>
                    <div class="column is-5">
                        <span>Jumlah data yang ditampilkan</span>
                        <VControl>
                            <VInput type="text" class="input" v-model="item.rows" />
                        </VControl>
                    </div>
                    <div class="column is-4"></div>
                </div>
                <div class="column is-12 pt-0 pb-0" v-if="listOrderLab.length">
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="table-tg" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="border center" width="10%">#</th>
                                    <th class="border center" width="20%">Tanggal Order</th>
                                    <th class="border center" width="20%">Nomor Order</th>
                                    <th class="border center" width="25%">Jenis Lab</th>
                                    <th class="border center" width="25%">Pemeriksaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="res in filteredOL">
                                    <td class="border center"
                                        style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="getDetailLab(res.noorder)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                    <td class="border center">{{ res.tglorder }}</td>
                                    <td class="border center">{{ res.noorder }}</td>
                                    <td class="border center">{{ res.lab }}</td>
                                    <td class="border" style="padding: 3px;">
                                        <div v-for="item in res.details">
                                            <span>- {{ item.namaproduk }}</span>
                                            <br>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="column is-12 pt-0 pb-0" v-else>
                    <span>Riwayat Order Lab tidak ada...</span>
                </div>
            </form>
        </template>
    </VModal>

    <VModal :open="showModalDetailOrderLab" title="List Detail Lab" :noclose="true" size="large" actions="right"
        @close="showModalDetailOrderLab = false; listDetailOrderLab = []">
        <template #content>
            <div class="column is-12 py-1">
                <VIconButton type="button" icon="feather:arrow-left" @click="turnBack('Laboratorium')" color="link"
                    v-tooltip.bubble="'Kembali'">
                </VIconButton>
            </div>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="table-tg" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="border center" width="90%">Hasil Pemeriksaan</th>
                                    <th class="border center" width="10%">#</th>
                                </tr>
                            </thead>
                            <tbody v-for="res in listDetailOrderLab.details">
                                <tr>
                                    <td class="border" style="padding: 3px;background-color: lightgoldenrodyellow"
                                        colspan="2">
                                        <h1>{{ res.group }}</h1>
                                    </td>
                                </tr>
                                <tr v-for="dt in res.items">
                                    <td class="border" style="padding: 3px;vertical-align: middle;">
                                        <!-- DETAIL PEMERIKSAAN -->
                                        <b>{{ dt.detailpemeriksaan }}</b>
                                        -
                                        <!-- FLAG -->
                                        <span v-if="dt.flag === 'H' && dt.hasil" style="color: red;">H</span>
                                        <span v-else-if="(dt.flag === 'L' || dt.flag === 'LL') && dt.hasil"
                                            style="color: blue;">{{ dt.flag }}</span>
                                        <span v-else-if="dt.flag === 'HH' && dt.hasil" style="color: red;">HH</span>
                                        <span v-else-if="dt.flag === '*' && dt.hasil" style="color: red;">*</span>
                                        <span v-else-if="dt.flag === 'N' && dt.hasil" style="color: black;">N</span>
                                        -
                                        <!-- HASIL PART 1 -->
                                        <span v-if="dt.result_ft" :style="{
                                            textAlign: 'center',
                                            color: dt.flag === 'H' || dt.flag === 'HH' || dt.flag === '*'
                                                ? 'red'
                                                : (dt.flag === 'L' || dt.flag === 'LL' ? 'blue' : '')
                                        }">
                                            {{ dt.result_ft }}
                                        </span>
                                        <span v-else :style="{
                                            textAlign: 'center', color: dt.flag === 'H' || dt.flag === 'HH' || dt.flag === '*' ? 'red'
                                                : (dt.flag === 'L' || dt.flag === 'LL' ? 'blue' : '')
                                        }">
                                            {{ dt.hasil }}
                                        </span>

                                        <!-- HASIL PART 2 -->
                                        <template v-if="dt.hasil">
                                            <br>
                                            <span
                                                style="white-space: nowrap; display: inline-block; text-align: center;">
                                                Satuan Standar : {{ dt.satuanstandar || '-' }}
                                            </span>
                                            <br>
                                            <span
                                                style="white-space: nowrap; display: inline-block; text-align: center;">
                                                Nilai Normal : {{ dt.nilaitext || '-' }}
                                            </span>
                                            <br>
                                            <span style="display: inline-block; text-align: center;">
                                                Keterangan :&nbsp;
                                                <span v-if="dt.flag === 'H'">Tinggi</span>
                                                <span v-else-if="dt.flag === 'LL' || dt.flag === 'L'">Rendah</span>
                                            </span>
                                            <br>
                                            <span
                                                style="white-space: nowrap; display: inline-block; text-align: center;">
                                                Metode : {{ dt.metode || '-' }}
                                            </span>
                                        </template>
                                    </td>
                                    <td class="border center"
                                        style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="setDetailLab(res.group, dt)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
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

    <VModal :open="showModalOrderRad" title="Riwayat Radiologi" :noclose="true" size="large" actions="right"
        @close="showModalOrderRad = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0" v-if="listOrderRad.length">
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="table-tg" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="border center" width="10%">#</th>
                                    <th class="border center" width="30%">Tanggal Order</th>
                                    <th class="border center" width="30%">Nomor Order</th>
                                    <th class="border center" width="30%">Pemeriksaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="res in listOrderRad">
                                    <td class="border center"
                                        style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="getDetailRad(res.noorder)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                    <td class="border center">{{ res.tglorder }}</td>
                                    <td class="border center">{{ res.noorder }}</td>
                                    <td class="border" style="padding: 3px;">
                                        <div v-for="item in res.details">
                                            <span>- {{ item.namaproduk }}</span>
                                            <br>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="column is-12 pt-0 pb-0" v-else>
                    <span>Riwayat Radiologi tidak ada...</span>
                </div>
            </form>
        </template>
    </VModal>

    <VModal :open="showModalDetailOrderRad" title="List Detail Radiologi" :noclose="true" size="large" actions="right"
        @close="showModalDetailOrderRad = false; listDetailOrderRad = []">
        <template #content>
            <div class="column is-12 py-1">
                <VIconButton type="button" icon="feather:arrow-left" @click="turnBack('Radiologi')" color="link"
                    v-tooltip.bubble="'Kembali'">
                </VIconButton>
            </div>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0" v-if="listDetailOrderRad.length">
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="table-tg" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="border center" width="10%">#</th>
                                    <th class="border center" width="30%">Nama Pemeriksaan</th>
                                    <th class="border center" width="60%">Expertise</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="res in listDetailOrderRad">
                                    <td class="border center"
                                        style="text-align:center;padding:5px;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="setDetailRad(res)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                    <td class="border center">{{ res.nama_pemeriksaan }}</td>
                                    <td class="border" style="padding: 3px;">
                                        <!-- {{ res.expertise_1 }} -->
                                        <!-- <br> -->
                                        {{ res.expertise_2 }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="column is-12 pt-0 pb-0" v-else>
                    <span>Riwayat Radiologi tidak ada...</span>
                </div>
            </form>
        </template>
    </VModal>
</template>

<script setup lang="ts">
import MasterEMR from './master-emr.vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, nextTick } from 'vue'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const masterRef = ref(null)
const dataPasien = '';
const d_Dokter: any = ref([]);
const d_Petugas: any = ref([]);
const d_Goldar: any = ref([{ value: 1, label: 'A' }, { value: 2, label: 'B' }, { value: 3, label: 'AB' }, { value: 4, label: 'O' }])
const d_Rhesus: any = ref([{ value: 1, label: 'Posistif' }, { value: 2, label: 'Negatif' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Lemah' }, { value: 4, label: 'Jelek' }])
const d_Ruangan: any = ref([{ value: 302, label: 'Bank Darah' }, { value: 335, label: 'Lab - PK' }, { value: 336, label: 'Lab - PA' }, { value: 337, label: 'Lab - Mikro' }])
const dataTTD: any = ref([])
const listVitalSign: any = ref([])
const listCPPT: any = ref([])
const listRiwayatCPPT: any = ref([])
const listAsmedRI: any = ref([])
const listObatPasien: any = ref([])
const totalLabCounts: any = ref({
    bank_darah: 0,
    lab_PK: 0,
    lab_PA: 0,
    lab_Mikro: 0
});
const listOrderLab: any = ref([]);
const listDetailOrderLab: any = ref([])
const listOrderRad: any = ref([])
const listDetailOrderRad: any = ref([])
const NOREC_EMRPASIEN: any = ref('')
const inputanDiagnosa: any = ref('')
const switchRiwayat: any = ref('')
const switchInputan: any = ref('')
const isLoading: any = ref(false);
const sectionLab: any = ref(false);
const sectionRad: any = ref(false);
const showModalOrderLab: any = ref(false);
const showModalDetailOrderLab: any = ref(false);
const showModalOrderRad: any = ref(false);
const showModalDetailOrderRad: any = ref(false);
const showModalVitalSign: any = ref(false);
const showModalCPPT: any = ref(false);
const showModalRiwayatCPPT: any = ref(false);
const showModalAsmedRI: any = ref(false);
const showModalRiwayatObatPasien: any = ref(false);
const showModalLaporanOperasi: any = ref(true);
const sectionObat: any = ref(0)
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const modalTindakan1 = ref(false)
const listTindakan1: any = ref([])

const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
        COLLECTION?: string
        input?: any
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
        COLLECTION: '',
        input: {},
    }
)
const input: any = ref({
    dokterRaber: [{ no: 1, }],
    details: [
        {
            NamaobatSelama: '',
            JmlSelama: '',
            DosisSelama: '',
            FrekuensiSelama: '',
            CarapemberianSelama: '',
            NamaobatSetelah: '',
            JmlSetelah: '',
            DosisSetelah: '',
            FrekuensiSetelah: '',
            CarapemberianSetelah: '',
        }
    ]
})

// const addNewItem = () => {
//   input.value.details.push({
//     NamaobatSelama: '',
//     JmlSelama: '',
//     DosisSelama: '',
//     FrekuensiSelama: '',
//     CarapemberianSelama: '',
//     NamaobatSetelah: '',
//     JmlSetelah: '',
//     DosisSetelah: '',
//     FrekuensiSetelah: '',
//     CarapemberianSetelah: '',
//   })
// }
const modalTindakan = ref(false);

const item: any = reactive({
    rows: 10,
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    DEPARTEMEN_FK: props.registrasi.objectdepartemenfk,
    registrasi: {
        ruanganfk: props.registrasi.objectruanganlastfk,
        departemenfk: props.registrasi.objectdepartemenfk,
    }

})


const lockedFormName = ref(props.FORM_NAME)
const lockedFormUrl = ref(props.FORM_URL)


const fetchDokter = async (filter: any) => {
    d_Dokter.value = await H.fetchDokter(filter);
}

const getRadio2 = () => {
    isLoading.value = true
    // let stringLabo = 'Labora';
    let uri = `radiologi/layanan-radiologi?norec_pd=${item.NOREC_PD}`;

    useApi().get(uri).then((res) => {
        let layananRadio = '';
        if (res && res.detail.length > 0) {
            for (let index = 0; index < res.detail.length; index++) {
                const group = res.detail[index];
                if (group.details.length > 0) {
                    for (let i = 0; i < group.details.length; i++) {
                        const detail = group.details[i];
                        if (detail.namaproduk != null) {
                            layananRadio += '# ' + detail.namaproduk + ' ';
                        }
                    }
                }
            }
            if (input.value.DiagnosaUtama != undefined || input.value.DiagnosaUtama != null) {
                input.value.DiagnosaUtama += '\nRadiologi : ' + layananRadio;
            } else {
                input.value.DiagnosaUtama = 'Radiologi : ' + layananRadio;
            }
            H.alert('success', 'Berhasil ditambahkan')
        } else {
            H.alert('warning', 'Belum ada riwayat')
        }
        isLoading.value = false
    })
}

const getLabo2 = () => {
    isLoading.value = true
    // let stringLabo = 'Labora';
    let uri = `laboratorium/riwayat-order?nocmfk=${props.pasien.nocmfk}&norec_pd=${item.NOREC_PD}`

    useApi().get(uri).then((res) => {
        let hasilLab = '';
        if (res.length > 0) {
            for (let index = 0; index < res.length; index++) {
                const element = res[index];
                if (element.details && element.details.length > 0) {
                    for (let i = 0; i < element.details.length; i++) {
                        const detail = element.details[i];
                        hasilLab += '# ' + detail.namaproduk + ' ';
                    }
                }
            }
            if (input.value.DiagnosaUtama != undefined || input.value.DiagnosaUtama != null) {
                input.value.DiagnosaUtama += '\nLaboratorium : ' + hasilLab;
            } else {
                input.value.DiagnosaUtama = 'Laboratorium : ' + hasilLab;
            }
            H.alert('success', 'Berhasil ditambahkan')
        } else {
            H.alert('warning', 'Belum ada riwayat')
        }
        isLoading.value = false
    })
}

const getRadio3 = () => {
    isLoading.value = true
    // let stringLabo = 'Labora';
    let uri = `radiologi/layanan-radiologi?norec_pd=${item.NOREC_PD}`;

    useApi().get(uri).then((res) => {
        let layananRadio = '';
        if (res && res.detail.length > 0) {
            for (let index = 0; index < res.detail.length; index++) {
                const group = res.detail[index];
                if (group.details.length > 0) {
                    for (let i = 0; i < group.details.length; i++) {
                        const detail = group.details[i];
                        if (detail.namaproduk != null) {
                            layananRadio += '# ' + detail.namaproduk + ' ';
                        }
                    }
                }
            }
            if (input.value.DiagnosaSekunder != undefined || input.value.DiagnosaSekunder != null) {
                input.value.DiagnosaSekunder += '\nRadiologi : ' + layananRadio;
            } else {
                input.value.DiagnosaSekunder = 'Radiologi : ' + layananRadio;
            }
            H.alert('success', 'Berhasil ditambahkan')
        } else {
            H.alert('warning', 'Belum ada riwayat')
        }
        isLoading.value = false
    })
}

const getLabo3 = () => {
    isLoading.value = true
    // let stringLabo = 'Labora';
    let uri = `laboratorium/riwayat-order?nocmfk=${props.pasien.nocmfk}&norec_pd=${item.NOREC_PD}`

    useApi().get(uri).then((res) => {
        let hasilLab = '';
        if (res.length > 0) {
            for (let index = 0; index < res.length; index++) {
                const element = res[index];
                if (element.details && element.details.length > 0) {
                    for (let i = 0; i < element.details.length; i++) {
                        const detail = element.details[i];
                        hasilLab += '# ' + detail.namaproduk + ' ';
                    }
                }
            }
            if (input.value.DiagnosaSekunder != undefined || input.value.DiagnosaSekunder != null) {
                input.value.DiagnosaSekunder += '\nLaboratorium : ' + hasilLab;
            } else {
                input.value.DiagnosaSekunder = 'Laboratorium : ' + hasilLab;
            }
            H.alert('success', 'Berhasil ditambahkan')
        } else {
            H.alert('warning', 'Belum ada riwayat')
        }
        isLoading.value = false
    })
}

const getPemeriksaanEKG = async (inputan: any) => {
    isLoading.value = true;
    const response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=FormulirHasilPemeriksaanEkg`)
    if (response.length > 0) {
        let hasilKonsultasi =
            // `P Wave: ${response[0].PWave || '-'}\n` +
            // `PR Interval: ${response[0].PRInterval || '-'}\n` +
            // `QT Interval: ${response[0].QTInterval || '-'}\n` +
            // `QRS: ${response[0].QRS || '-'}\n` +
            // `ST Segment: ${response[0].STSegment || '-'}\n` +
            // `T Wave: ${response[0].TWave || '-'}\n` +
            // `HR: ${response[0].HR || '-'}\n` +
            // `Axis: ${response[0].Axis || '-'}\n` +
            // `Other: ${response[0].Other || '-'}\n` +
            `EKG Conclusion: ${response[0].Conclusion || '-'}`;

        input.value[`${inputan}`] += hasilKonsultasi;
    } else {
        input.value[`${inputan}`] = '';
        H.alert('warning', 'Data Pemeriksaan EKG belum ada');
    }
    isLoading.value = false;
}

const getPemeriksaanEKG_V2 = async (inputan: any) => {
    isLoading.value = true;
    let d = input.value;
    let str = '';
    let gcol = `FormulirHasilPemeriksaanEkg,TransThoracaEchoBayi,TransThoracaEchoDewasa,LowerExtermityDuplexUltrasoundUSGDoppler,CarotidDuplexUltrasound`;
    await useApi().get(`emr/get-penunjang-khusus?tables=${gcol}&norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`).then((dt) => {
        if (dt.length > 0) {
            for (let kObject = 0; kObject < dt.length; kObject++) {
                const val = dt[kObject];
                if (val.table && (val.Conclusion || val.conclusion)) {
                    str += `${formatString(val.table)} \n`;
                    // Perbedaan inputan saja
                    str += val.conclusion ? `Conclusion : ${val.conclusion} \n` : ''
                    str += val.Conclusion ? `Conclusion : ${val.Conclusion} \n` : ''
                }
            }

            if (!d[inputan]) {
                d[inputan] = str
            } else {
                d[inputan] = d[inputan].trim() + '\n' + str + '\n';
            }
        } else {
            H.alert('warning', 'Data tidak ada!')
        }
    }).catch((e: any) => {
        console.log(e)
        H.alert('error', 'Terjadi Kesalahan')
    }).finally(() => {
        isLoading.value = false
    });
}

function formatString(input) {
    let spaced = input.replace(/([A-Z])/g, ' $1').trim();
    const abbreviations = {
        'E K G': 'EKG',
        'U S G': 'USG'
    };

    for (const [pattern, replacement] of Object.entries(abbreviations)) {
        const regex = new RegExp(`\\b${pattern}\\b`, 'gi');
        spaced = spaced.replace(regex, replacement);
    }

    spaced = spaced.replace(/\bEkg\b/, 'EKG'); 
    spaced = spaced.replace(/\bUsg\b/, 'USG'); 

    return spaced;
}

const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
}

const getLabo = () => {
    isLoading.value = true
    // let stringLabo = 'Labora';
    let uri = `laboratorium/riwayat-order?nocmfk=${props.pasien.nocmfk}&norec_pd=${item.NOREC_PD}`

    useApi().get(uri).then((res) => {
        let hasilLab = '';
        if (res.length > 0) {
            for (let index = 0; index < res.length; index++) {
                const element = res[index];
                if (element.details && element.details.length > 0) {
                    for (let i = 0; i < element.details.length; i++) {
                        const detail = element.details[i];
                        hasilLab += '# ' + detail.namaproduk + ' ';
                    }
                }
            }
            if (input.value.PemeriksaanDiagnostik) {
                input.value.PemeriksaanDiagnostik += '\nLaboratorium : ' + hasilLab;
            } else {
                input.value.PemeriksaanDiagnostik = 'Laboratorium : ' + hasilLab;
            }
            H.alert('success', 'Berhasil ditambahkan')
        } else {
            H.alert('warning', 'Belum ada riwayat')
        }
        isLoading.value = false
    })
}
const isLoadingTindakan = ref(false)

const getTindakan = async () => {
    isLoadingTindakan.value = true;
    const response = await useApi().get(`kasir/list-tindakan-pasien-emr?nocmfk=${props.pasien.nocmfk}&norec_pd=${item.NOREC_PD}`);

    if (response.length > 0) {
        const semuaTindakan = response.map(tindakan => {
            return `- ${tindakan.namaproduk}`;
        }).join('\n');

        input.value.ProsedurTerapi = `Tindakan yang dilakukan:\n${semuaTindakan}`;
    } else {
        input.value.ProsedurTerapi = 'Tidak ada data tindakan';
    }

    isLoadingTindakan.value = false;
};
const isLoadingTindakan1 = ref(false)
const cariObat = ref('');
const getListTindakan = async () => {
    isLoadingTindakan1.value = true
    const response = await useApi().get(`emr/get-obat-by-pasien?noregistrasi=${props.registrasi.noregistrasi}`)
    if (response.length > 0) {
        listTindakan1.value = response
        modalTindakan1.value = true
    } else {
        H.alert('warning', 'Belum ada riwayat')
    }
    isLoadingTindakan1.value = false
}
// const filteredTindakan = computed(() => {
//     if (!cariObat.value) {
//         // Jika tidak ada keyword pencarian, gabungkan semua detail
//         return listTindakan1.value.flatMap(item => item.details);
//     }

//     return listTindakan1.value.flatMap(item =>
//         item.details.filter(detail =>
//             detail.namaproduk.toLowerCase().includes(cariObat.value.toLowerCase())
//         )
//     );
// });
const filteredTindakan = computed(() => {
    if (!cariObat.value) {
        return listTindakan1.value; // Return all if no search term
    }

    const searchTerm = cariObat.value.toLowerCase();
    return listTindakan1.value.filter(item =>
        item.namaproduk?.toLowerCase().includes(searchTerm)
    );
});

const listTindakan100: any = ref([])
const modalTindakan100 = ref(false)
const cariTindakan100 = ref('')
const isloadingTindakan100 = ref(false)
const getListTindakan100 = async () => {
    isloadingTindakan100.value = true
    const response = await useApi().get(`kasir/list-tindakan-pasien-emr?nocmfk=${props.pasien.nocmfk}&norec_pd=${item.NOREC_PD}`).then((res) => {
        if (res.length > 0) {
            listTindakan100.value = res
            modalTindakan100.value = true
            isloadingTindakan100.value = false
        } else {
            H.alert('warning', 'Belum ada riwayat tindakan')
        }
    }).catch((err) => {
        isloadingTindakan100.value = false
        console.log(err);
    });
}
const filteredTindakan100 = computed(() => {
    if (!cariTindakan100.value) {
        // Jika tidak ada keyword pencarian, gabungkan semua detail
        return listTindakan100.value
    }

    return listTindakan100.value.filter((item) =>
        item.namaproduk.toLowerCase().includes(cariTindakan100.value.toLowerCase())
    );
});
const addTindakan100 = (tindakan: any) => {
    if (input.value.ProsedurTerapi == undefined || input.value.ProsedurTerapi == null || input.value.ProsedurTerapi == '') {
        input.value.ProsedurTerapi = `Tindakan yang dilakukan:\n- ${tindakan}`;
        H.alert('success', `Tindakan ${tindakan} ditambahkan`)
    } else {
        input.value.ProsedurTerapi += `\n- ${tindakan}`;
        H.alert('success', `Tindakan ${tindakan} ditambahkan`)
    }
}



const isLoadingTindakan2 = ref(false)
const listTindakan2: any = ref([])
const modalTindakan2: any = ref(false)
const getListTindakan2 = async () => {
    isLoadingTindakan2.value = true
    modalTindakan2.value = true
    const response = await useApi().get(`kasir/list-tindakan-pasien-emr?nocmfk=${props.pasien.nocmfk}&norec_pd=${item.NOREC_PD}`);
    listTindakan2.value = response
    isLoadingTindakan2.value = false
}
const cariTindakan2 = ref('');
const filteredTindakan2 = computed(() => {
    if (!cariTindakan2.value) return listTindakan2.value;

    return listTindakan2.value.filter((item) =>
        item.namaproduk.toLowerCase().includes(cariTindakan2.value.toLowerCase())
    );
})
const addTindakan = (tindakan: any) => {
    if (input.value.ProsedurTerapi == undefined || input.value.ProsedurTerapi == null || input.value.ProsedurTerapi == '') {
        input.value.ProsedurTerapi = `Terapi:\n- ${tindakan}`;
        H.alert('success', `Terapi ${tindakan} ditambahkan`)
    } else {
        input.value.ProsedurTerapi += `\n- ${tindakan}`;
        H.alert('success', `Terapi ${tindakan} ditambahkan`)
    }
}
const addTindakan2 = (tindakan: any) => {
    if (input.value.TindakanDikerjakan == undefined || input.value.TindakanDikerjakan == null || input.value.TindakanDikerjakan == '') {
        input.value.TindakanDikerjakan = `Tindakan yang dilakukan:\n- ${tindakan}`;
        H.alert('success', `Tindakan ${tindakan} ditambahkan`)
    } else {
        input.value.TindakanDikerjakan += `\n- ${tindakan}`;
        H.alert('success', `Tindakan ${tindakan} ditambahkan`)
    }
}

const getRadio = () => {
    isLoading.value = true
    // let stringLabo = 'Labora';
    let uri = `radiologi/layanan-radiologi?norec_pd=${item.NOREC_PD}`;

    useApi().get(uri).then((res) => {
        let layananRadio = '';
        if (res && res.detail.length > 0) {
            for (let index = 0; index < res.detail.length; index++) {
                const group = res.detail[index];
                if (group.details.length > 0) {
                    for (let i = 0; i < group.details.length; i++) {
                        const detail = group.details[i];
                        if (detail.namaproduk != null) {
                            layananRadio += '# ' + detail.namaproduk + ' ';
                        }
                    }
                }
            }
            if (input.value.PemeriksaanDiagnostik) {
                input.value.PemeriksaanDiagnostik += '\nRadiologi : ' + layananRadio;
            } else {
                input.value.PemeriksaanDiagnostik = 'Radiologi : ' + layananRadio;
            }
            H.alert('success', 'Berhasil ditambahkan')
        } else {
            H.alert('warning', 'Belum ada riwayat')
        }
        isLoading.value = false
    })
}

const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist-pertama?nocmfk=${ID_PASIEN}`).then((response) => {
        if (response) {
            input.value.celcius = response.suhu
            input.value.nafas = response.pernapasan
            input.value.BB = response.beratBadan
            input.value.nadi = response.nadi
            input.value.Spo = response.SPO2
            // input.value.tekananDarah = response.tekananDarah
            // input.value.gcse = response.GCSe
            // input.value.gcsv = response.GCSv
            // input.value.gcsm = response.GCSm
        }
    })
}

const setAutoFill = async () => {
    let d = input.value;
    let reg = props.registrasi;

    d.DPJP = reg.dokter
    d.DpjpUtama = { value: reg.iddokter, label: reg.dokter }
    d.DokterPenanggungJawab = { value: reg.iddokter, label: reg.dokter }
    d.ruangan = reg.namaruangan
    d.Carabayar = reg.kelompokpasien
    d.Hasilkonsultasi = reg.dokterrawatbersama
    d.DTttd = new Date()
    d.Jam = new Date()
    d.tanggalPengisian = new Date()
    d.tanggalKeluar = reg.tglpulang;
    d.tanggalMRS = reg.tglsep ? reg.tglsep : reg.tglregistrasi;
    d.tanggalKRS = reg.tglclosing ? reg.tglclosing : reg.tglpulang;
    d.dokterRaber = [{ no: 1, }];

    const response_CPPTFullDetail = await useApi().get("emr/get-emr-cppt?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CatatanPerkembanganPasienTerintegrasi");
    const response_CPPT = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CPPTDetail" + `&field=A`)
    // const response_CatatanPemberianObat = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CatatanPemberianObat" + `&field=details,details2,details3,D_1_CPO,D_2_CPO,D_3_CPO,D_4_CPO,D_5_CPO,TB_1_CPO,TB_2_CPO,TB_3_CPO,TB_4_CPO,TB_5_CPO`)
    const response_CatatanPemberianObat = null;
    const response_AsmedRanap = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenMedisRawatInap" + `&field=TADiagnosa,indikasi`)
    const response_RingkasanKeluar = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=RingkasanKeluar" + `&field=TADiagnosisPrimer`)
    const response_VitalSign = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=VitalSign" + `&field=suhu,pernapasan,nadi,tekananDarah`)
    const check_departemen_masuk_pasien = await useApi().get("emr/check-departemen-masuk-pasien?norec_pd=" + NOREC_PD)

    if (check_departemen_masuk_pasien) {
        let dt = check_departemen_masuk_pasien;
        let d = input.value;

        switch (dt['objectdepartemenfk']) {
            case 9:
                if (dt['objectruanganfk'] == '322') { d.IGD = 'IGD'; }
                else if (dt['objectruanganfk'] == '323') { d.VK = 'VK'; }
                break;
            case 18:
                d.POLIKLINIK = 'POLIKLINIK';
                break;
            case 45:
                d.OK = 'OK';
                break;
            default:
                break;
        }
    }

    if (response_AsmedRanap != null) {
        input.value.IndikasiRawatInap = response_AsmedRanap.indikasi;
        input.value.MasalahDiagnosamasuk = response_AsmedRanap.TADiagnosa;
        input.value.sebabkekerasan = response_AsmedRanap.MOI;
        input.value.SKeadaanUmum = response_AsmedRanap.keadaanumum;
        input.value.TBtekananDarahTTV = response_AsmedRanap.TBtekananDarahTTV;
        input.value.TBnadiTTV = response_AsmedRanap.TBnadiTTV;
        input.value.TBPernafasanTTV = response_AsmedRanap.TBPernafasanTTV;
        input.value.TBcelciusTTV = response_AsmedRanap.TBcelciusTTV;
        input.value.TBnspo2TTV = response_AsmedRanap.TBnspo2TTV;
        input.value.TBberatBadanTTV = response_AsmedRanap.TBberatBadanTTV;
        input.value.TBtinggiBadanTTV = response_AsmedRanap.TBtinggiBadanTTV;
    }

    if (response_VitalSign != null) {
        input.value.celciusKeluar = response_VitalSign.suhu
        input.value.nafasKeluar = response_VitalSign.pernapasan
        input.value.nadiKeluar = response_VitalSign.nadi
        input.value.tekananDarahKeluar = response_VitalSign.tekananDarah
    }

    if (response_CPPTFullDetail && response_CPPTFullDetail.length > 0) {
        let object = response_CPPTFullDetail[0]; // Declare 'object' with 'let'
        let d = input.value;

        d.riwayatkeluar = object.riwayatkeluar
        d.PenyebabKematian = object.keluar
        if (object.riwayatkeluar == 'Meninggal >= 48 Jam') {
            d.riwayatkeluar = 'Meninggal ≥ 48 Jam'
        } else if (object.riwayatkeluar == 'Meninggal <= 48 Jam') {
            d.riwayatkeluar = 'Meninggal ≤ 48 Jam'
        }
    };

    if (response_CatatanPemberianObat != null) {
        let outputText = "Instruksi Pengobatan:\n\n";

        response_CatatanPemberianObat.details.forEach(item => {
            const date = new Date(item.DTanggal_IP).toLocaleDateString("id-ID");
            outputText += `${item.no}. Tanggal: ${date}\n`;
            outputText += `   Nama Obat: ${item.TBNamaObat}\n`;
            outputText += `   Dosis: ${item.TBDosis}\n`;
            outputText += `   Frekuensi: ${item.listInjeksi}\n`;
            outputText += `   Rute: ${item.TBRute}\n`;
            outputText += `   Paraf Dokter: ${item.DDParafDokter ? item.DDParafDokter.label : ''}\n`;
            outputText += `   Paraf Apoteker: ${item.DDParafDokter ? item.DDParafApoteker.label : ''}\n\n`;
        });

        console.log(outputText);
        input.value.ProsedurTerapi = outputText

        const formattedText = ["Catatan Pemberian Obat :\n"];
        response_CatatanPemberianObat.details.forEach((entry, index) => {
            const namaObat = entry.TBNamaObat || "Nama obat tidak tersedia";

            // Ambil daftar tanggal pemberian
            const tanggalPemberianList = [
                response_CatatanPemberianObat.D_1_CPO, response_CatatanPemberianObat.D_2_CPO, response_CatatanPemberianObat.D_3_CPO, response_CatatanPemberianObat.D_4_CPO, response_CatatanPemberianObat.D_5_CPO,
                ...response_CatatanPemberianObat.details3.map(item => item.tanggalPengisian)
            ].filter(t => t); // Hanya ambil tanggal yang tersedia

            if (!tanggalPemberianList.length) {
                formattedText.push(`Nama Obat: ${namaObat} (Tidak ada tanggal pemberian)`);
                return;
            }

            const waktuPemberianPerTanggal = {};
            const parafPemberiPerTanggal = {};

            if (response_CatatanPemberianObat.details2[index]) {
                const keys = Object.keys(response_CatatanPemberianObat.details2[index]);
                const jumlahData = keys.filter(key => key.startsWith("Time_")).length;

                for (let i = 0; i < jumlahData; i++) {
                    let waktu = response_CatatanPemberianObat.details2[index][`Time_${i}`];

                    // Lewati jika timestamp besar (> 1000000000000)
                    if (!isNaN(waktu) && Number(waktu) > 1000000000000) {
                        continue;
                    }

                    // Gunakan tanggal dari daftar berdasarkan indeks (1 tanggal = 7 data waktu)
                    let tanggalIndex = Math.floor(i / 7);
                    let tanggalPemberian = tanggalPemberianList[tanggalIndex] || "Tanggal tidak tersedia";

                    let formattedWaktu = "Waktu tidak tersedia";

                    if (waktu && typeof waktu === "string") {
                        if (waktu.startsWith("T")) {
                            waktu = + waktu;
                        }
                        const date = new Date(waktu);
                        if (!isNaN(date.getTime())) {
                            formattedWaktu = date.toISOString().split("T")[1].slice(0, 5); // Ambil HH:mm
                        }
                    }

                    const paraf1 = response_CatatanPemberianObat.details2[index][`DDParaf1_${i}`]?.label;
                    const paraf2 = response_CatatanPemberianObat.details2[index][`DDParaf2_${i}`]?.label;

                    if (!waktuPemberianPerTanggal[tanggalPemberian]) {
                        waktuPemberianPerTanggal[tanggalPemberian] = [];
                        parafPemberiPerTanggal[tanggalPemberian] = new Set();
                    }

                    if (formattedWaktu !== "Waktu tidak tersedia") {
                        waktuPemberianPerTanggal[tanggalPemberian].push(formattedWaktu);
                    }

                    if (paraf1) parafPemberiPerTanggal[tanggalPemberian].add(paraf1);
                    if (paraf2) parafPemberiPerTanggal[tanggalPemberian].add(paraf2);
                }
            }

            console.log(waktuPemberianPerTanggal);
            // Format hasil untuk setiap tanggal pemberian
            Object.keys(waktuPemberianPerTanggal).forEach((tanggal) => {
                const formattedTanggal = new Date(tanggal).toISOString().split("T")[0];
                formattedText.push(
                    `------------------------------------------\n` +
                    `Nama Obat      : ${namaObat}\n` +
                    `Tanggal        : ${formattedTanggal}\n` +
                    `Waktu          : ${waktuPemberianPerTanggal[tanggal].join(", ")}\n` +
                    `Paraf Pemberi  : ${[...parafPemberiPerTanggal[tanggal]].join(", ") || "Paraf tidak tersedia"}\n`
                );
            });
        });

        input.value.ProsedurTerapi += formattedText.join("\n");
    }
};

const loadRiwayat = async () => {
    isLoading.value = true;
    await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
        if (response.length) {
            input.value = response[0] //set ke inputan
            if (!input.value.id) {
                input.value.id = response[0].id;
            }
            if (NOREC_EMRPASIEN.value == '') {
                NOREC_EMRPASIEN.value = response[0].emrpasienfk
            }
            if (!input.value.dokterRaber) {
                input.value.dokterRaber = [{ no: 1, }];
            }
            dataTTD.value = response[0]
            nextTick(() => {
                H.tandaTangan().set('TTDpasien', dataTTD.value.TTDpasien)
            })
        }
        else {
            H.alert('info', 'Sedang mengambil data')
            await setAutoFill()
            H.alert('info', 'Berhasil mengambil data')
        }
    })
    isLoading.value = false;
}

const simpan = () => {
    if (!input.value.IndikasiRawatInap) {
        H.alert('warning', 'Indikasi Rawat Inap wajib diisi');
        return;
    }
    if (!input.value.AlergiReaksiObat) {
        H.alert('warning', 'Alergi (Reaksi Obat) wajib diisi');
        return;
    }
    if (!input.value.Dietdiberikan) {
        H.alert('warning', 'Diet Yang Telah Diberikan & Diet Yang Harus Dilakukan di Rumah Diet wajib diisi');
        return;
    }
    if (!input.value.InstruksiTindakLanjut) {
        H.alert('warning', 'Instruksi Tindak Lanjut wajib diisi');
        return;
    }
    if (!input.value.Anamnesis) {
        H.alert('warning', ' Ringkasan Riwayat Kesehatan wajib diisi');
        return;
    }
    if (!input.value.kondisiUmum) {
        H.alert('warning', 'Kondisi Umum wajib diisi');
        return;
    }
    if (!input.value.celcius) {
        H.alert('warning', 'Suhu wajib diisi');
        return;
    }
    if (!input.value.nafas) {
        H.alert('warning', 'Pernafasan wajib diisi');
        return;
    }
    if (!input.value.BB) {
        H.alert('warning', 'Berat Badan(BB) wajib diisi');
        return;
    }
    if (!input.value.nadi) {
        H.alert('warning', 'Nadi wajib diisi');
        return;
    }
    if (!input.value.tekananDarah) {
        H.alert('warning', 'Tekanan Darah wajib diisi');
        return;
    }
    if (!input.value.Spo) {
        H.alert('warning', 'SpO2 wajib diisi');
        return;
    }
    if (!input.value.gcse) {
        H.alert('warning', 'GCS E wajib diisi');
        return;
    }
    if (!input.value.gcsv) {
        H.alert('warning', 'GCS V wajib diisi');
        return;
    }
    if (!input.value.gcsm) {
        H.alert('warning', 'GCS M wajib diisi');
        return;
    }
    if (!input.value.DiagnosaUtama) {
        H.alert('warning', 'Diagnosa utama wajib diisi');
        return;
    }
    if (!input.value.TindakanDikerjakan) {
        H.alert('warning', 'Tindakan Yang Telah Dikerjakan wajib diisi');
        return;
    }
    if (!input.value.kondisiUmumKeluar) {
        H.alert('warning', 'Kondisi dan Tanda-tanda Vital Pasien Waktu Keluar RS wajib diisi');
        return;
    }
    if (!input.value.celciusKeluar) {
        H.alert('warning', 'Kondisi dan Tanda-tanda Vital Pasien Waktu Keluar RS wajib diisi');
        return;
    }
    if (!input.value.nafasKeluar) {
        H.alert('warning', 'Kondisi dan Tanda-tanda Vital Pasien Waktu Keluar RS wajib diisi');
        return;
    }
    if (!input.value.nadiKeluar) {
        H.alert('warning', 'Kondisi dan Tanda-tanda Vital Pasien Waktu Keluar RS wajib diisi');
        return;
    }
    if (!input.value.tekananDarahKeluar) {
        H.alert('warning', 'Kondisi dan Tanda-tanda Vital Pasien Waktu Keluar RS wajib diisi');
        return;
    }
    if (!input.value.riwayatkeluar) {
        H.alert('warning', 'Status Pasien Waktu Keluar RS wajib diisi');
        return;
    }



    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['TTDpasien'] = H.tandaTangan().get('TTDpasien')
    delete object._id
    delete object.namatemplate

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
            loadRiwayat();
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const simpanTemplate = () => {
    if (!input.value.namatemplate) {
        H.alert('warning', "Nama Template wajib diisi")
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

    useApi().post(
        `/emr/simpan-emr-template`, json).then((response: any) => {
            isLoading.value = false
            input.value.namatemplate = null
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const addNewItem = () => {
    let newItem: any = {}
    newItem = {
        no: input.value.details[input.value.details.length - 1].no + 1,
    }
    input.value.details.push(newItem);
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}

const addNewItem_Dokter = () => {
    let newItem: any = {}
    newItem = {
        no: input.value.dokterRaber[input.value.dokterRaber.length - 1].no + 1,
    }
    input.value.dokterRaber.push(newItem);
}
const removeItem_Dokter = (index: any) => {
    input.value.dokterRaber.splice(index, 1)
}

const addNewDPJP = () => {
    input.value.rawatbersama.push({
        no: input.value.rawatbersama[input.value.rawatbersama.length - 1].no + 1,
    });
}
const removeDPJP = (index: any) => {
    input.value.rawatbersama.splice(index, 1)
}

const addNewPeralihan = () => {
    input.value.peralihanDPJP.push({
        no: input.value.peralihanDPJP[input.value.peralihanDPJP.length - 1].no + 1,
    });
}
const removePeralihan = (index: any) => {
    input.value.peralihanDPJP.splice(index, 1)
}

const triggerAllData = async () => {
    if (masterRef.value) {
        let ss = await loadRiwayat()
        if (ss != null) {
            input.value = ss
        }
    }
}

const riwayatVitalSign = async (inputan: any) => {
    isLoading.value = true;
    useApi().get(`emr/get-data-exist-semua?norec_pd=${NOREC_PD}`).then((responselast: any) => {
        isLoading.value = false;
        if (responselast.length) {
            listVitalSign.value = responselast;
            switchInputan.value = inputan;
            showModalVitalSign.value = true;
        } else {
            H.alert('warning', 'Data vital sign tidak ada!');
        }
    });
};

const riwayatCPPT = async (inputan: any) => {
    isLoading.value = true;
    useApi().get(`emr/get-data-exist-semua-cppt?norec_pd=${NOREC_PD}`).then((responselast: any) => {
        isLoading.value = false;
        if (responselast.length) {
            listCPPT.value = responselast;
            switchInputan.value = inputan; // ITL (untuk saat ini)
            showModalCPPT.value = true;
        } else {
            H.alert('warning', 'Data CPPT tidak ada!');
        }
    });
};

const addRiwayatVitalSign = (response: any) => {
    let d = input.value;

    if (switchInputan.value == 'PF') {
        d.celcius = response.suhu ? response.suhu : ''
        d.nafas = response.pernapasan ? response.pernapasan : ''
        d.nadi = response.nadi ? response.nadi : ''
        d.tekananDarah = response.tekananDarah ? response.tekananDarah : ''
        d.Spo = response.SPO2 ? response.SPO2 : ''
        d.BB = response.beratBadan ? response.beratBadan : ''
        d.gcse = response.GCSe ? response.GCSe : ''
        d.gcsv = response.GCSv ? response.GCSv : ''
        d.gcsm = response.GCSm ? response.GCSm : ''
    } else if (switchInputan.value == 'TTV_Keluar_RS') {
        d.celciusKeluar = response.suhu ? response.suhu : ''
        d.nafasKeluar = response.pernapasan ? response.pernapasan : ''
        d.nadiKeluar = response.nadi ? response.nadi : ''
        d.tekananDarahKeluar = response.tekananDarah ? response.tekananDarah : ''
    }

    switchInputan.value = '';
    showModalVitalSign.value = false;
};

const addRiwayatCPPT_All = (data: any) => {
    let d = input.value;

    if (switchInputan.value == 'ITL') {
        if (!d.InstruksiTindakLanjut) {
            d.InstruksiTindakLanjut = data.P ? `${data.P} \n` : ''
        } else {
            d.InstruksiTindakLanjut += data.P ? `${data.P} \n` : ''
        }
    } else if (switchInputan.value == 'PTYTD') {
        if (!d.ProsedurTerapi) {
            d.ProsedurTerapi = data.P ? `${data.P} \n` : ''
        } else {
            d.ProsedurTerapi += data.P ? `${data.P} \n` : ''
        }
    } else if (switchInputan.value == 'HK') {
        if (!d.ProsedurTerapi) {
            d.Hasilkonsultasi = data.P ? `${data.P} \n` : ''
        } else {
            d.Hasilkonsultasi += data.P ? `${data.P} \n` : ''
        }
    }

    switchInputan.value = '';
    showModalCPPT.value = false;
};

const riwayat_Alergi = async (jenis: any) => {
    let dataPS = `nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`;
    let api = 'emr/auto-fill?'
    let collection = '';
    let field = '';
    let param = '';

    switch (jenis) {
        case 'Asesmen Medis RI':
            collection = '&collection=AsesmenMedisRawatInap';
            field = '&field=alergiReaksiObat'
            break;
        case 'Asesmen Medis Neonatus':
            collection = '&collection=AsesmenAwalMedisNeonatusRI';
            field = '&field=TAAlergiReaksiObat'
            break;
        default:
            H.alert('error', 'Terjadi Kesalahan')
            break;
    }

    isLoading.value = true
    await useApi().get(`${api}${dataPS}${collection}${field}${param}`).then((res: any) => {
        let text = '';
        if (res) {
            if (collection == '&collection=AsesmenMedisRawatInap') {
                text += res.alergiReaksiObat ? `Alergi (Reaksi Obat) : ${res.alergiReaksiObat}\n` : '';
            } else if (collection == '&collection=AsesmenAwalMedisNeonatusRI') {
                text += res.TAAlergiReaksiObat ? `Alergi (Reaksi Obat) : ${res.TAAlergiReaksiObat}\n` : '';
            }
        } else {
            H.alert('warning', 'Alergi obat tidak ditemukan')
        }

        text = text.trim();

        if (!input.value.AlergiReaksiObat) {
            input.value.AlergiReaksiObat = text
        } else {
            input.value.AlergiReaksiObat = input.value.AlergiReaksiObat.trim() + '\n' + text + '\n';
        }

    }).catch((e: any) => {
        console.log(e)
        H.alert('error', 'Terjadi Kesalahan')
    }).finally(() => {
        isLoading.value = false
    });
}

const riwayat_Diet = async (jenis: any) => {
    let dataPS = `nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`;
    let api = 'emr/auto-fill?'
    let collection = '';
    let field = '';
    let param = '';

    switch (jenis) {
        case 'Asesmen Medis RI':
            collection = '&collection=AsesmenMedisRawatInap';
            field = '&field=dietDiberikan'
            break;
        case 'Asesmen Medis Neonatus':
            collection = '&collection=AsesmenAwalMedisNeonatusRI';
            field = '&field=TADiet'
            break;
        default:
            H.alert('error', 'Terjadi Kesalahan')
            break;
    }

    isLoading.value = true
    await useApi().get(`${api}${dataPS}${collection}${field}${param}`).then((res: any) => {
        let text = '';
        if (res) {
            if (collection == '&collection=AsesmenMedisRawatInap') {
                text += res.dietDiberikan ? `${res.dietDiberikan}\n` : '';
            } else if (collection == '&collection=AsesmenAwalMedisNeonatusRI') {
                text += res.TADiet ? `${res.TADiet}\n` : '';
            }
        } else {
            H.alert('warning', 'Tidak ada diet yang diberikan')
        }

        text = text.trim();

        if (!input.value.Dietdiberikan) {
            input.value.Dietdiberikan = text
        } else {
            input.value.Dietdiberikan = input.value.Dietdiberikan.trim() + '\n' + text + '\n';
        }

    }).catch((e: any) => {
        console.log(e)
        H.alert('error', 'Terjadi Kesalahan')
    }).finally(() => {
        isLoading.value = false
    });
}

const autoFillEMR = async (jenis: any, inputan: any, riwayat: any) => {
    let dataPS = `nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`;
    let api = 'emr/auto-fill?';
    let collection = '';
    let field = '';
    let param = '';
    let text = '';
    let d = input.value;

    // Set riwayat apa yang mau diambil
    switchRiwayat.value = riwayat; // Diagnosa, RRK, KELBAYI (untuk saat ini)

    // Set collection, dan param
    switch (jenis) {
        case 'Asesmen Medis RI':
            api = 'emr/get-emr?';
            collection = '&collection=AsesmenMedisRawatInap';
            break;
        case 'Asesmen Medis Neonatus':
            collection = '&collection=AsesmenAwalMedisNeonatusRI';
            if (riwayat == 'Diagnosa') { field = '&field=TADiagnosa'; }
            else if (riwayat == 'RRK') { field = '&field=TAAnamnesis'; }
            else if (riwayat == 'KELBAYI') { field = '&field=TAKeluhanBayi,TAAnamnesis'; }
            else if (riwayat == 'IRI') { field = '&field=TAIndikasiRI'; }
            break;
        case 'Asesmen Medis IGD':
            collection = '&collection=AsesmenAwalMedisGawatDarurat';
            if (riwayat == 'Diagnosa') { field = '&field=TADiagnosis'; }
            else if (riwayat == 'RRK') { field = '&field=Select_Allo,TAKeluhanUtama,TARiwayatPenyakitDahulu,TARiwayatPenggunaanObat,TARiwayatVaksin,TARPS,TAMOI,TBAlergiObat,TBAlergiMakanan,TBAlergiLainnya'; }
            break;
        case 'CPPT':
            api = 'emr/get-emr-cppt?';
            collection = '&collection=CatatanPerkembanganPasienTerintegrasi';
            param = '&flag=dokter';
            break;
        default:
            H.alert('error', 'Terjadi Kesalahan');
            return '';
    }

    isLoading.value = true;
    try {
        const res = await useApi().get(`${api}${dataPS}${collection}${field}${param}`);
        if (res) {
            if (collection == '&collection=AsesmenMedisRawatInap') {
                if (res.length) {
                    listAsmedRI.value = res;
                    showModalAsmedRI.value = true;
                    inputanDiagnosa.value = inputan;
                } else {
                    H.alert('warning', 'Data Asesmen Medis Rawat Inap Tidak Ditemukan!');
                }
            } else if (collection == '&collection=AsesmenAwalMedisNeonatusRI') {
                if (riwayat == 'Diagnosa') {
                    text += res.TADiagnosa ? `Diagnosa : ${res.TADiagnosa}\n` : '';
                } else if (riwayat == 'RRK') {
                    text += res.TAAnamnesis ? `Anamnesis : ${res.TAAnamnesis}\n` : '';
                } else if (riwayat == 'KELBAYI') {
                    text += res.TAAnamnesis ? `Anamnesis : ${res.TAAnamnesis}\n` : '';
                    text += res.TAKeluhanBayi ? `Keluhan Bayi : ${res.TAKeluhanBayi}\n` : '';
                } else if (riwayat == 'IRI') {
                    text += res.TAIndikasiRI ? `Indikasi Rawat Inap : ${res.TAIndikasiRI}\n` : '';
                }
            } else if (collection == '&collection=AsesmenAwalMedisGawatDarurat') {
                if (riwayat == 'Diagnosa') {
                    text += res.TADiagnosis ? `Diagnosa : ${res.TADiagnosis}\n` : '';
                } else if (riwayat == 'RRK') {
                    let allo = '';
                    if (res.Select_Allo) {
                        switch (res.Select_Allo) {
                            case 1:
                                allo = 'Suami/Istri';
                                break;
                            case 2:
                                allo = 'Orang Tua';
                                break;
                            case 3:
                                allo = 'Anak';
                                break;
                            case 4:
                                allo = 'Pasien';
                                break;
                            case 5:
                                allo = 'Lainnya';
                                break;
                            default:
                                allo = '-';
                                break;
                        }
                    }

                    // text += `Alloanamnesis : ${allo} \n`
                    text += 'Anamnesis : \n'
                    text += 'Keluhan Utama : ' + (res.TAKeluhanUtama ? `${res.TAKeluhanUtama} \n` : '- \n');
                    text += 'Riwayat Penyakit Dahulu : ' + (res.TARiwayatPenyakitDahulu ? `${res.TARiwayatPenyakitDahulu} \n` : '- \n');
                    text += 'Riwayat Penggunaan Obat : ' + (res.TARiwayatPenggunaanObat ? `${res.TARiwayatPenggunaanObat} \n` : '- \n');
                    text += 'Riwayat Vaksin : ' + (res.TARiwayatVaksin ? `${res.TARiwayatVaksin} \n` : '- \n');
                    text += 'Riwayat Sekarang : ' + (res.TARPS ? `${res.TARPS} \n` : '- \n');
                    // text += 'MOI : ' + (res.TAMOI ? `${res.TAMOI} \n` : '- \n');

                    if (res.isalergi == 'YA') {
                        text += 'Alergi Obat : ' + (res.TBAlergiObat ? `${res.TBAlergiObat} \n` : '- \n');
                        text += 'Alergi Makanan : ' + (res.TBAlergiMakanan ? `${res.TBAlergiMakanan} \n` : '- \n');
                        text += 'Alergi Lainnya : ' + (res.TBAlergiLainnya ? `${res.TBAlergiLainnya} \n` : '- \n');
                    }
                }
            } else if (collection == '&collection=CatatanPerkembanganPasienTerintegrasi') {
                if (res.length && res[0].detailsFull) {
                    listRiwayatCPPT.value = res[0].detailsFull;
                    showModalRiwayatCPPT.value = true;
                    inputanDiagnosa.value = inputan;
                } else {
                    H.alert('warning', 'Data CPPT Dokter tidak ada!');
                }
            }

            // Menghilangkan Spasi yang berlebihan
            text = text.trim();

            // Set Inputan
            switch (riwayat) {
                case 'Diagnosa':
                    if (inputan == 'DU') {
                        if (!d.DiagnosaUtama) { d.DiagnosaUtama = text; }
                        else { d.DiagnosaUtama = d.DiagnosaUtama.trim() + '\n' + text + '\n'; }
                    }
                    else if (inputan == 'DS') {
                        if (!d.DiagnosaSekunder) { d.DiagnosaSekunder = text; }
                        else { d.DiagnosaSekunder = d.DiagnosaSekunder.trim() + '\n' + text + '\n'; }
                    }
                    else if (inputan == 'Komorbiditas') {
                        if (!d.Komorbiditas) { d.Komorbiditas = text; }
                        else { d.Komorbiditas = d.Komorbiditas.trim() + '\n' + text + '\n'; }
                    }
                    break;
                case 'RRK':
                    if (!d.Anamnesis) { d.Anamnesis = text; }
                    else { d.Anamnesis = d.Anamnesis.trim() + '\n' + text + '\n'; }
                    break;
                case 'KELBAYI':
                    if (!d.Anamnesis) { d.Anamnesis = text; }
                    else { d.Anamnesis = d.Anamnesis.trim() + '\n' + text + '\n'; }
                    break;
                case 'IRI':
                    if (!d.IndikasiRawatInap) { d.IndikasiRawatInap = text; }
                    else { d.IndikasiRawatInap = d.IndikasiRawatInap.trim() + '\n' + text + '\n'; }
                    break;
                default:
                    break;
            }
        } else {
            H.alert('warning', 'Data tidak ditemukan!');
            return '';
        }
    } catch (e) {
        console.log(e);
        H.alert('error', 'Terjadi Kesalahan');
        return '';
    } finally {
        isLoading.value = false;
    }
};

const addRiwayatCPPT = (data: any) => {
    let d = input.value;
    // Supaya spasinya tidak berjauhan
    ['A', 'S', 'O', 'P'].forEach(key => {
        data[key] = data[key].trim();
    });

    switch (switchRiwayat.value) {
        case 'Diagnosa':
            if (inputanDiagnosa.value == 'DU') {
                if (!d.DiagnosaUtama) {
                    d.DiagnosaUtama = data.A;
                } else {
                    d.DiagnosaUtama = d.DiagnosaUtama.trim() + '\n' + data.A + '\n';
                }
            } else if (inputanDiagnosa.value == 'DS') {
                if (!d.DiagnosaSekunder) {
                    d.DiagnosaSekunder = data.A;
                } else {
                    d.DiagnosaSekunder = d.DiagnosaSekunder.trim() + '\n' + data.A + '\n';
                }
            } else if (inputanDiagnosa.value == 'Komorbiditas') {
                if (!d.Komorbiditas) {
                    d.Komorbiditas = data.A;
                } else {
                    d.Komorbiditas = d.Komorbiditas.trim() + '\n' + data.A + '\n';
                }
            }
            break;
        case 'RRK':
            if (!d.Anamnesis) {
                d.Anamnesis = data.A;
            } else {
                d.Anamnesis = d.Anamnesis.trim() + '\n' + data.A + '\n';
            }
            break;
        case 'TindakanDikerjakan':
            if (!d.TindakanDikerjakan) {
                d.TindakanDikerjakan = data.P;
            } else {
                d.TindakanDikerjakan = d.TindakanDikerjakan.trim() + '\n' + data.P + '\n';
            }
            break;
        default:
            break;
    }

    showModalRiwayatCPPT.value = false;
    inputanDiagnosa.value = '';
    switchRiwayat.value = '';
}

const addRiwayatAsmedRI = (data: any) => {
    let text = '';
    let d = input.value;

    switch (switchRiwayat.value) {
        case 'Diagnosa':
            data = data.trim();
            if (inputanDiagnosa.value == 'DU') {
                if (!d.DiagnosaUtama) {
                    d.DiagnosaUtama = data;
                } else {
                    d.DiagnosaUtama = d.DiagnosaUtama.trim() + '\n' + data + '\n';
                }
            } else if (inputanDiagnosa.value == 'DS') {
                if (!d.DiagnosaSekunder) {
                    d.DiagnosaSekunder = data;
                } else {
                    d.DiagnosaSekunder = d.DiagnosaSekunder.trim() + '\n' + data + '\n';
                }
            } else if (inputanDiagnosa.value == 'Komorbiditas') {
                if (!d.Komorbiditas) {
                    d.Komorbiditas = data;
                } else {
                    d.Komorbiditas = d.Komorbiditas.trim() + '\n' + data + '\n';
                }
            }
            break;
        case 'RRK':
            text += `Anamnesis : ${data.anamnesis}`;
            if (!d.Anamnesis) {
                d.Anamnesis = text;
            } else {
                d.Anamnesis = d.Anamnesis.trim() + '\n' + text + '\n';
            }
            break;
        case 'IRI':
            data = data.trim();
            if (!d.IndikasiRawatInap) {
                d.IndikasiRawatInap = data;
            } else {
                d.IndikasiRawatInap = d.IndikasiRawatInap.trim() + '\n' + data + '\n';
            }
            break;
        case 'LYB':
            text += 'Kepala \n';
            text += data.kepala ? `Kepala : ${data.ketKepala || data.kepala} \n` : '';
            text += data.ubunubun ? `Ubun Ubun Besar : ${data.ketUbunubun || data.ubunubun} \n` : '';
            text += data.normal ? `Normal : ${data.normal} \n` : '';
            text += data.mikrosefali ? `Mikrosefali : ${data.mikrosefali} \n` : '';
            text += data.lingkarkepala ? `Lingkar Kepala : ${data.ketLingkarkepala || data.lingkarkepala} \n` : '';
            text += data.lingkarlainnya ? `Lainnya : ${data.ketLingkarlainnya || data.lingkarlainnya} \n` : '';
            text += data.makrosefali ? `Makrosefali : ${data.makrosefali} \n` : '';

            text += '\nMata \n';
            text += data.anemis ? `Anemis : ${data.ketAnemis} \n` : '';
            text += data.konjungtiva ? `Konjungtiva Pucat : ${data.ketKonjungtiva.label || data.ketKonjungtiva} \n` : '';
            text += data.pupil ? `Pupil Isokor : ${data.ketPupil.label || data.ketPupil} \n` : '';
            text += data.ikterus ? `Ikterus : ${data.ketIkterus || data.ikterus} \n` : '';
            text += data.hiperemi ? `Hiperemi : ${data.ketHiperemi.label || data.ketHiperemi} \n` : '';
            text += data.refleks ? `Refleks Cahaya : ${data.ketRefleks || data.refleks} \n` : '';
            text += data.refpupil ? `Refleks Pupil : ${data.ketRefpupil || data.refpupil} \n` : '';
            text += data.secret ? `Secret : ${data.ketSecret.label || data.ketSecret} \n` : '';
            text += data.oedema ? `Oedema : ${data.ketOedema.label || data.ketOedema} \n` : '';
            text += data.oedemapal ? `Oedema Palpebrae : ${data.ketOedemapal || data.oedemapal} \n` : '';
            text += data.skleraik ? `Sklera Ikteris : ${data.ketSkleraik.label || data.ketSkleraik} \n` : '';

            text += '\nTHT \n';
            text += data.tonsil ? `Tonsil : ${data.ketTonsil} \n` : '';
            text += data.hidung ? `Hidung : ${data.ketHidung} \n` : '';
            text += data.lainlain ? `Lain-Lain : ${data.ketLain} \n` : '';
            text += data.pharing ? `Pharing : ${data.ketPharing} \n` : '';
            text += data.bibir ? `Bibir : ${data.ketBibir} \n` : '';
            text += data.lidah ? `Lidah : ${data.ketLidah} \n` : '';
            text += data.telinga ? `Telinga : ${data.ketTelinga} \n` : '';

            text += '\nLeher \n';
            text += data.jvp ? `JVP : ${data.ketJVP} \n` : '';
            text += data.kakukuduk ? `Kaku Kuduk : ${data.kakukuduk} \n` : '';
            text += data.tunggal ? `Tunggal : ${data.tunggal} \n` : '';
            text += data.kelenjar ? `Pembesaran Kelenjar : ${data.ketKelenjar || data.kelenjar} \n` : '';
            text += data.lainnya ? `Lainnya : ${data.ketLainnya} \n` : '';
            text += data.multiple ? `Multiple : ${data.multiple} \n` : '';

            text += '\nThorax \n';
            text += data.simetris ? `Simetris : ${data.ketSimetris || data.simetris} \n` : '';
            text += data.retraksi ? `Retraksi : ${data.ketRetraksi || data.retraksi} \n` : '';

            text += '\nCOR \n';
            text += data.cornormal ? `Inspeksi Iktus Kordis - Normal: ${data.cornormal} \n` : '';
            text += data.melebar ? `Melebar: ${data.ketMelebar || data.melebar} \n` : '';
            text += data.ketLokasi ? `Lokasi Iktus Kordis: ${data.ketLokasi} \n` : '';
            text += data.apex ? `Pulsasi Apex: ${data.apex} \n` : '';
            text += data.prekordium ? `Pulsasi Prekordium: ${data.prekordium} \n` : '';
            text += data.epigastrium ? `Pulsasi Epigastrium: ${data.epigastrium} \n` : '';
            text += data.corlainnya ? `Pulsasi Lainnya: ${data.ketLainnya || data.corlainnya} \n` : '';
            text += data.s1s2 ? `Suara Jantung Utama: ${data.s1s2} \n` : '';
            text += data.ketTunggal ? `Keterangan S1, S2: ${data.ketTunggal} \n` : '';
            text += data.regular ? `Irama: ${data.regular} \n` : '';
            text += data.iregular ? `Irama: ${data.iregular} \n` : '';
            text += data.systole ? `Extra Systole: ${data.systole} \n` : '';
            text += data.gallop ? `Gallop: ${data.gallop} \n` : '';
            text += data.palpasinormal ? `Palpasi Iktus Kordis - Normal: ${data.palpasinormal} \n` : '';
            text += data.kuatangkat ? `Kuat Angkat: ${data.kuatangkat} \n` : '';
            text += data.meluas ? `Meluas: ${data.meluas} \n` : '';
            text += data.ketLokasiPalpasi ? `Lokasi Palpasi: ${data.ketLokasiPalpasi} \n` : '';
            text += data.sistolik ? `Thrill Sistolik: ${data.sistolik} \n` : '';
            text += data.diastolik ? `Thrill Diastolik: ${data.diastolik} \n` : '';
            text += data.murmur ? `Suara Tambahan - Murmur: ${data.ketMurmur || data.murmur} \n` : '';
            text += data.batasatas ? `Batas Atas: ${data.ketBatasatas || data.batasatas} \n` : '';
            text += data.batasbawah ? `Batas Bawah: ${data.ketBatasbawah || data.batasbawah} \n` : '';
            text += data.bataskanan ? `Batas Kanan: ${data.ketBataskanan || data.bataskanan} \n` : '';
            text += data.bataskiri ? `Batas Kiri: ${data.ketBataskiri || data.bataskiri} \n` : '';

            text += '\nPulmo \n';
            text += data.statis ? `Inspeksi Statis: ${data.ketStatis || data.statis} \n` : '';
            text += data.perkusi ? `Perkusi: ${data.ketPerkusi || data.perkusi} \n` : '';
            text += data.auskultasi ? `Auskultasi Vesikuler: ${data.ketAuskultasi || data.auskultasi} \n` : '';
            text += data.dinamis ? `Inspeksi Dinamis: ${data.ketDinamis || data.dinamis} \n` : '';
            text += data.ronchi ? `Ronchi: ${data.ketRonchi || data.ronchi} \n` : '';
            text += data.nafas ? `Suara Nafas: ${data.ketNafas || data.nafas} \n` : '';
            text += data.palpasi ? `Palpasi: SF: ${data.ketPalpasi || data.palpasi} \n` : '';
            text += data.wheezing ? `Auskultasi Wheezing: ${data.ketWheezing || data.wheezing} \n` : '';
            text += data.lainnyapulmo ? `Lain-lain: ${data.ketLainnyapulmo || data.lainnyapulmo} \n` : '';

            if (data.ketPeristaltik) {
                switch (data.ketPeristaltik) {
                    case 1:
                        data.ketPeristaltik = 'Peristaltik-> Normal';
                        break;
                    case 2:
                        data.ketPeristaltik = 'Peristaltik-> Meningkat';
                        break;
                    case 3:
                        data.ketPeristaltik = 'Peristaltik-> Menurun';
                        break;
                    default:
                        break;
                }
            }

            text += '\nAbdomen \n';
            text += data.ketPeristaltik ? `Peristaltik: ${data.ketPeristaltik} \n` : '';
            text += data.distensi ? `Distensi: ${data.ketDistensi || data.distensi} \n` : '';
            text += data.meteorismus ? `Meteorismus: ${data.ketMeteorismus || data.meteorismus} \n` : '';
            text += data.souffle ? `Souffle: ${data.ketSouffle || data.souffle} \n` : '';
            text += data.ascites ? `Ascites: ${data.ketAscites || data.ascites} \n` : '';
            text += data.turgor ? `Turgor: ${data.ketTurgor || data.turgor} \n` : '';
            text += data.nyeri ? `Nyeri Tekan Lokasi: ${data.ketNyeri || data.nyeri} \n` : '';
            text += data.lien ? `Lien: ${data.ketLien || data.lien} \n` : '';
            text += data.massa ? `Massa: ${data.ketMassa || data.massa} \n` : '';
            text += data.hepar ? `Hepar: ${data.ketHepar || data.hepar} \n` : '';

            text += '\nExtremitas \n';
            text += data.batasnormalextremitas ? `Dalam Batas Normal: ${data.ketExtremitas || data.batasnormalextremitas} \n` : '';
            text += data.odema ? `Odema: ${data.ketOdema || data.odema} \n` : '';
            text += data.capillary ? `Capillary Refill Time: ${data.ketCapillary || data.capillary} \n` : '';
            text += data.lanlan ? `Lain-lain: ${data.ketLanlan || data.lanlan} \n` : '';

            text += '\nLainnya \n';
            text += data.Kulit ? `Kulit : ${data.ketKulit || data.Kulit} \n` : '';
            text += data.pubertasp ? `Pubertas Perempuan : ${data.ketPubertasp || data.pubertasp} \n` : '';
            text += data.lala ? `Lain-lain : ${data.ketLala || data.lala} \n` : '';
            text += data.genetalia ? `Genetalia Eloxterna : ${data.ketGenetalia || data.genetalia} \n` : '';
            text += data.pubertasl ? `Pubertas Laki-Laki : ${data.ketPubertasl || data.pubertasl} \n` : '';

            if (!d.Lainnyayangbermakna) {
                d.Lainnyayangbermakna = text;
            } else {
                d.Lainnyayangbermakna = d.Lainnyayangbermakna.trim() + '\n' + text + '\n';
            }
            break;
        default:
            break;
    }

    showModalAsmedRI.value = false;
    inputanDiagnosa.value = '';
    switchRiwayat.value = '';
}

// GET DATA LABORATORIUM
const filteredOL = computed(() => {
    if (item.ruangan) {
        return listOrderLab.value.filter(dt => dt.objectruangantujuanfk == item.ruangan).slice(0, item.rows);
    } else {
        return listOrderLab.value.slice(0, item.rows);
    }
});

const getOrderLab = async (data: any) => {
    isLoading.value = true;
    await useApi().get(`emr/get-order-lab?noregistrasi=${props.registrasi.noregistrasi}`).then((res: any) => {
        if (res.length) {
            totalLabCounts.value = {
                bank_darah: res.filter(dt => dt.objectruangantujuanfk === 302).length,
                lab_PK: res.filter(dt => dt.objectruangantujuanfk === 335).length,
                lab_PA: res.filter(dt => dt.objectruangantujuanfk === 336).length,
                lab_Mikro: res.filter(dt => dt.objectruangantujuanfk === 337).length
            };
            listOrderLab.value = res;
            showModalOrderLab.value = true;
        } else {
            H.alert('warning', 'Tidak ada orderan lab!')
        }
    }).catch((e: any) => {
        console.log(e)
        H.alert('warning', 'Terjadi kesalahan!')
    }).finally(() => {
        isLoading.value = false
    });
}

const getDetailLab = async (data: any) => {
    showModalOrderLab.value = false;
    isLoading.value = true;
    await useApi().get(`emr/get-detail-hasil-lab?noorder=${data}`).then((res: any) => {
        if (res.details) {
            listDetailOrderLab.value = res;
            showModalDetailOrderLab.value = true;
        }
    }).catch((e: any) => {
        console.log(e)
        H.alert('warning', 'Terjadi kesalahan!')
    }).finally(() => {
        isLoading.value = false
    });
}

const setDetailLab = (judul: any, data: any) => {
    let text = '';
    let dt = data;

    // if (!sectionLab.value) { text += '===== Laboratorium ===== \n'; }
    text += judul + '\n';
    // text += `${dt.detailpemeriksaan} - ${dt.flag} \n`;
    text += `Hasil : ${dt.result_ft ? dt.result_ft : dt.hasil} \n`;
    // text += `Satuan Standar : ${dt.satuanstandar} \n`;
    // text += `Nilai Normal : ${dt.nilaitext} \n`;
    // if (dt.flag == 'H') {
    //     text += `Keterangan : Tinggi \n`;
    // } else if (dt.flag == 'L' || dt.flag == 'LL') {
    //     text += `Keterangan : Rendah \n`;
    // }
    // text += `Metode : ${dt.metode ?? '-'} \n\n`;

    // Mencegah ketika hasilnya undefined
    if (input.value.PemeriksaanDiagnostik_Lab == undefined) { input.value.PemeriksaanDiagnostik_Lab = ''; }

    if (!input.value.PemeriksaanDiagnostik_Lab) {
        input.value.PemeriksaanDiagnostik_Lab = text;
    } else {
        input.value.PemeriksaanDiagnostik_Lab = input.value.PemeriksaanDiagnostik_Lab.trim() + '\n' + text + '\n';
    }

    // showModalDetailOrderLab.value = false;
    // sectionLab.value = true; // Param untuk pemisah section Laboratorium
    // listOrderLab.value = [];
    // listDetailOrderLab.value = [];
}

const getOrderRad = async (data: any) => {
    isLoading.value = true;
    await useApi().get(`emr/get-order-radiologi?noregistrasi=${props.registrasi.noregistrasi}`).then((res: any) => {
        if (res.length) {
            listOrderRad.value = res;
            showModalOrderRad.value = true;
        } else {
            H.alert('warning', 'Tidak ada orderan radiologi!')
        }
    }).catch((e: any) => {
        console.log(e)
        H.alert('warning', 'Terjadi kesalahan!')
    }).finally(() => {
        isLoading.value = false
    });
}

const getDetailRad = async (data: any) => {
    showModalOrderRad.value = false;
    isLoading.value = true;
    await useApi().get(`emr/get-detail-hasil-radiologi?noorder=${data}`).then((res: any) => {
        if (res.length) {
            listDetailOrderRad.value = res;
            showModalDetailOrderRad.value = true;
        }
    }).catch((e: any) => {
        console.log(e)
        H.alert('warning', 'Terjadi kesalahan!')
    }).finally(() => {
        isLoading.value = false
    });
}

const setDetailRad = (data: any) => {
    let text = '';

    // if (!sectionRad.value) { text += '===== Radiologi ===== \n'; }
    text += data.nama_pemeriksaan + '\n';
    // text += `Expertise : \n`;
    // text += `${data.expertise_1 ?? '-'} \n`;
    text += `${data.expertise_2 ?? '-'} \n`;

    // Mencegah ketika hasilnya undefined
    if (input.value.PemeriksaanDiagnostik_Rad == undefined) { input.value.PemeriksaanDiagnostik_Rad = ''; }

    if (!input.value.PemeriksaanDiagnostik_Rad) {
        input.value.PemeriksaanDiagnostik_Rad = text;
    } else {
        input.value.PemeriksaanDiagnostik_Rad = input.value.PemeriksaanDiagnostik_Rad.trim() + '\n' + text + '\n';
    }

    // showModalDetailOrderRad.value = false;
    // sectionRad.value = true; // Param untuk pemisah section Radiologi
    // listOrderRad.value = [];
    // listDetailOrderRad.value = [];
}

// Turn Back Modal
const turnBack = (jenis: any) => {
    switch (jenis) {
        case 'Laboratorium':
            showModalDetailOrderLab.value = false;
            showModalOrderLab.value = true;
            listDetailOrderLab.value = [];
            break;
        case 'Radiologi':
            showModalDetailOrderRad.value = false;
            showModalOrderRad.value = true;
            listDetailOrderRad.value = [];
            break;
        case 'Penunjang':

            break;
        default:
            break;
    }
};

const cetakLaporanOperasi = async () => {
    H.printBlade(`emr/cetak/laporanOperasi?pdf=true&noregistrasi=${props.registrasi.noregistrasi}`)
}

const setPenunjang = async (inputan: any) => {
    isLoading.value = true;
    let d = input.value;
    let str = '';
    let gcol = `PemeriksaanKardiotokografi,PemeriksaanObstetri,PemeriksaanGynekologi,PemeriksaanFetal`;
    await useApi().get(`emr/get-penunjang-khusus?tables=${gcol}&norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`).then((dt) => {
        isLoading.value = false;
        if (dt.length > 0) {
            for (let kObject = 0; kObject < dt.length; kObject++) {
                const val = dt[kObject];
                if (val.table == 'PemeriksaanKardiotokografi') {
                    str += val.jenisPemeriksaanObgyn ? `Jenis Pemeriksaan : ${formatString(val.jenisPemeriksaanObgyn)}\n` : ''
                    str += val.tdawal ? `TD Awal : ${val.tdawal}\n` : ''
                    str += val.tg15 ? `TD Menit ke 15 : ${val.tg15}\n` : ''
                    str += val.carapantau ? `Cara Pantau : ${val.carapantau}\n` : ''
                    str += val.kecepatankertas ? `Kecepatan Kertas : ${val.kecepatankertas} cm/menit\n` : ''
                    str += val.periksaDalam ? `Periksa Dalam : ${val.periksaDalam}\n` : ''
                    str += val.denganhasil ? `Dengan Hasil : ${val.denganhasil}\n` : ''
                    str += val.diagnosis ? `Diagnosis : ${val.diagnosis}\n` : ''
                    str += val.denyutjantung ? `Denyut Jantung Janin : ${val.denyutjantung}\n` : ''
                    str += val.frekuensidasar ? `Frekuensi Dasar : ${val.frekuensidasar}\n` : ''
                    str += val.akselerasi ? `Akselerasi : ${val.akselerasi}\n` : ''
                    str += val.deselerasi ? `Deselerasi : ${val.deselerasi}\n` : ''
                    str += val.variabilitas ? `Variabilitas : ${val.variabilitas}\n` : ''
                    str += val.jenisnya ? `Jenisnya : ${val.jenisnya}\n` : ''
                    str += val.beratnya ? `Beratnya : ${val.beratnya}\n` : ''
                    str += val.ssp ? `Pola disfungsi SSP : ${val.ssp}\n` : ''
                    str += val.yaitu ? `Yaitu : ${val.yaitu}\n` : ''
                    str += val.kontraksi ? `Kontraksi Uterus/His : ${val.kontraksi}\n` : ''
                    str += val.frekuensi ? `Frekuensi : ${val.frekuensi} /10menit\n` : ''
                    str += val.kekuatan ? `Kekuatan : ${val.kekuatan} mmHg\n` : ''
                    str += val.lamanya ? `Lamanya : ${val.lamanya} menit\n` : ''
                    str += val.relaksasi ? `Relaksasi : ${val.relaksasi}\n` : ''
                    str += val.konfigurasi ? `Konfigurasi : ${val.konfigurasi}\n` : ''
                    str += val.tumusdasar ? `Tumus Dasar : ${val.tumusdasar} mmHg\n` : ''
                    str += val.gerakjanin ? `Gerak Janin : ${val.gerakjanin} kali\n` : ''
                    str += val.lamagerak ? `dalam : ${val.lamagerak} menit\n` : ''
                    str += val.diagnosisktg ? `Diagnosis KTG : ${val.diagnosisktg}\n` : ''
                    str += val.kategoridiagnosisktg ? `Kategori : ${val.kategoridiagnosisktg}\n` : ''
                    str += val.saran ? `Saran : ${val.saran}\n` : ''
                } else if (val.table == 'PemeriksaanObstetri' || val.table == 'PemeriksaanFetal') {
                    str += val.jenisPemeriksaanObgyn ? `Jenis Pemeriksaan : ${formatString(val.jenisPemeriksaanObgyn)}\n` : ''
                    str += val.kondisiTeknisFetal ? `Kondisi Teknis : ${val.kondisiTeknisFetal} \n` : ''
                    str += val.karenaFetal ? `Karena : ${val.karenaFetal} \n` : ''
                    str += val.janin ? `Janin : ${val.janin} \n` : ''
                    str += val.jumlahJanin ? `Jumlah Janin : ${val.jumlahJanin} \n` : ''
                    str += val.khorionisitas ? `Khorionisitas : ${val.khorionisitas} \n` : ''
                    str += val.djj ? `DJJ : ${val.djj} \n` : ''
                    str += val.ketDJJ ? `Keterangan DJJ : ${val.ketDJJ} x/menit\n` : ''
                    str += val.fetalmovement ? `Fetal Movement : ${val.fetalmovement}\n` : ''
                    str += val.gestasionalsac || val.usiaGestasional || val.ketGestasional ? `Biometri :\n` : ''
                    str += val.gestasionalsac ? `Gestasional sac : ${val.gestasionalsac} - ${val.usiaGestasional ?? ''}\n` : ''
                    str += val.ketGestasional ? `AVE : ${val.ketGestasional}\n` : ''
                    str += val.crown || val.usiaCrown || val.ketCrown ? `Crown-rump lenght : ${val.crown} - ${val.usiaCrown}\n` : ''
                    str += val.ketCrown ? `EDD : ${val.ketCrown} \n` : ''
                    str += val.biparietal || val.usiaBiparietal ? `Biparietal Diameter : ${val.biparietal} - ${val.usiaBiparietal} \n` : ''
                    str += val.ketBiparietal ? `EFW : ${val.ketBiparietal} \n` : ''
                    str += val.headcircum || val.usiaHeadcircum ? `Head Circumference : ${val.headcircum} - ${val.usiaHeadcircum} \n` : ''
                    str += val.abdominalcircum || val.usiaAbdominalcircum ? `Abdominal Circumference : ${val.abdominalcircum} - ${val.usiaAbdominalcircum} \n` : ''
                    str += val.Ketabdominalcircum ? `Keterangan Abdominalcircum : ${val.Ketabdominalcircum} \n` : ''
                    str += val.femoral || val.usiaFemoral ? `Femoral Lenght : ${val.femoral} - ${val.usiaFemoral} \n` : ''
                    str += val.plasenta ? `Plasenta : ${val.plasenta} \n` : ''
                    str += val.menutupi ? `Menutupi : ${val.menutupi} \n` : ''
                    str += val.ukuranMenutupi ? `Ukuran Menutupi : ${val.ukuranMenutupi} mm dari OUI\n` : ''
                    str += val.maturasi ? `Maturasi : ${val.maturasi} \n` : ''
                    str += val.cairanaminion ? `Cairan aminion : ${val.cairanaminion} \n` : ''
                    str += val.AFI ? `AFI : ${val.AFI} \n` : ''
                    str += val.SDP ? `SDP : ${val.SDP} \n` : ''
                    str += val.temuanAbnormal ? `Temuan Abnormal : ${val.temuanAbnormal} \n` : ''
                    str += val.kongenitalMayor ? `Kelainan kongenital mayor : ${val.kongenitalMayor} \n` : ''
                    str += val.temuanAbnormalKongenital ? `Temuan Abnormal Kongenital : ${val.temuanAbnormalKongenital} \n` : ''
                    str += val.adneksa ? `Adneksa : ${val.adneksa} \n` : ''
                    str += val.temuanAbnormalAdneksa ? `Temuan Abnormal Adneksa : ${val.temuanAbnormalAdneksa} \n` : ''
                    str += val.arteriUterina ? `Arteri Uterina : ${val.arteriUterina} \n` : ''
                    str += val.arteriUmbilicalis ? `Arteri Umbilicalis : ${val.arteriUmbilicalis} \n` : ''
                    str += val.riUterina ? `RI Uterina: ${val.riUterina} \n` : ''
                    str += val.riUmbilicalis ? `RI Umbilicalis : ${val.riUmbilicalis} \n` : ''
                    str += val.piUterina ? `PI Uterina : ${val.piUterina} \n` : ''
                    str += val.piUmbilicalis ? `PI Umbilicalis: ${val.piUmbilicalis} \n` : ''
                    str += val.ratioUterina ? `S/D Ratio Uterina: ${val.ratioUterina} \n` : ''
                    str += val.ratioUmbilicalis ? `S/D Ratio Umbilicalis: ${val.ratioUmbilicalis} \n` : ''
                    str += val.ductusVenosus ? `Ductus Venosus : ${val.ductusVenosus} \n` : ''
                    str += val.arteriSerebi ? `Arteri Serebi Media : ${val.arteriSerebi} \n` : ''
                    str += val.riDuctus ? `RI Ductus : ${val.riDuctus} \n` : ''
                    str += val.riSerebi ? `RI Serebi : ${val.riSerebi} \n` : ''
                    str += val.piDuctus ? `PI Ductus: ${val.piDuctus} \n` : ''
                    str += val.piSerebi ? `PI Serebi: ${val.piSerebi} \n` : ''
                    str += val.ratioDuctus ? `S/D Ratio Ductus: ${val.ratioDuctus} \n` : ''
                    str += val.ratioSerebi ? `S/D Ratio Serebi: ${val.ratioSerebi} \n` : ''
                    str += val.fetalLainnya ? `Lain-Lain : ${val.fetalLainnya} \n` : ''
                    str += val.fetalSaran ? `Kesimpulan & Saran : ${val.fetalSaran} \n` : ''

                } else if (val.table == 'PemeriksaanGynekologi') {
                    str += val.jenisPemeriksaanObgyn ? `Jenis Pemeriksaan : ${formatString(val.jenisPemeriksaanObgyn)}\n` : ''
                    str += val.kondisiTeknis ? `Kondisi Teknis : ${val.kondisiTeknis} \n` : ''
                    str += val.karena ? `Karena : ${val.karena} \n` : ''
                    str += val.vesicaUrinaria ? `Vesica Urinaria : ${val.vesicaUrinaria} \n` : ''
                    str += val.cairanBebas ? `Cairan Bebas : ${val.cairanBebas} \n` : ''
                    str += val.uterus ? `Uterus : ${val.uterus} \n` : ''
                    str += val.adnexa ? `Adnexa : ${val.adnexa} \n` : ''
                    str += val.obstetriLainnya ? `Lain-Lain : ${val.obstetriLainnya} \n` : ''
                    str += val.kesimpulansaran ? `Kesimpulan & Saran : ${val.kesimpulansaran} \n` : ''
                }
            }

            if (!d[inputan]) {
                d[inputan] = str
            } else {
                d[inputan] = d[inputan].trim() + '\n' + str + '\n';
            }
        } else {
            H.alert('warning', 'Data tidak ada!')
        }
    });
}

const riwayatObatPasien = async (index: any) => {
    isLoading.value = true;
    let resObat = await useApi().get(`/farmasi/riwayat-order-resep?norec_pd=${NOREC_PD}`)
    if (resObat.length > 0) {
        sectionObat.value = index;
        if (sectionObat.value == 1) {
            listObatPasien.value = resObat;
        } else if (sectionObat.value == 2) {
            let filterObatBPL = resObat.filter(item => item.isbpl === true);
            listObatPasien.value = filterObatBPL;
            if (filterObatBPL.length == 0) { H.alert('warning', 'Obat BPL tidak ada!') }
        }
        showModalRiwayatObatPasien.value = true
    } else {
        H.alert('warning', 'Belum ada Obat');
    }
    isLoading.value = false;
}

const inputObatPasien = ref('')
const listRiwayatObatPasien = computed(() => {
    if (!inputObatPasien.value.trim()) return listObatPasien.value;

    const searchTerm = inputObatPasien.value.toLowerCase().trim();
    return listObatPasien.value.filter((item) => {
        // maap
        const productName = item.details?.[0]?.namaproduk?.toLowerCase() || '';
        return productName.includes(searchTerm);
    });
});

const addObat = (data: any, jenis: any) => {
    let create_new = false;
    let index = 0;
    let newItem: any = {};
    let d = input.value.details;

    if (sectionObat.value == 1) {
        // This for loop function is for check empty index obat selamat rawat inap
        for (let i = 0; i < d.length; i++) {
            if (!d[i].NamaobatSelama && !d[i].JmlSelama && !d[i].DosisSelama && !d[i].FrekuensiSelama && !d[i].CarapemberianSelama) {
                index = i;
                create_new = false;
                break;
            } else {
                create_new = true;
            }
        }

        if (create_new == false) {
            d[index].NamaobatSelama = data.namaproduk;
            d[index].JmlSelama = data.jumlah;
            d[index].DosisSelama = data.dosis;
            d[index].FrekuensiSelama = '-';
            d[index].CarapemberianSelama = data.aturanpakai;
        } else if (create_new == true) {
            newItem = {
                no: input.value.details[input.value.details.length - 1].no + 1,
                NamaobatSelama: data.namaproduk,
                JmlSelama: data.jumlah,
                DosisSelama: data.dosis,
                FrekuensiSelama: '-',
                CarapemberianSelama: data.aturanpakai
            }
            input.value.details.push(newItem);
        }
    } else if (sectionObat.value == 2) {
        // This for loop function is for check empty index obat setelah rawat inap
        for (let i = 0; i < d.length; i++) {
            if (!d[i].NamaobatSetelah && !d[i].JmlSeNamaobatSetelah && !d[i].DosisSeNamaobatSetelah && !d[i].FrekuensiSeNamaobatSetelah && !d[i].CarapemberianSeNamaobatSetelah) {
                index = i;
                create_new = false;
                break;
            } else {
                create_new = true;
            }
        }

        if (create_new == false) {
            d[index].NamaobatSetelah = data.namaproduk;
            d[index].JmlSetelah = data.jumlah;
            d[index].DosisSetelah = data.dosis;
            d[index].FrekuensiSetelah = '-';
            d[index].CarapemberianSetelah = data.aturanpakai;
        } else if (create_new == true) {
            newItem = {
                no: input.value.details[input.value.details.length - 1].no + 1,
                NamaobatSetelah: data.namaproduk,
                JmlSetelah: data.jumlah,
                DosisSetelah: data.dosis,
                FrekuensiSetelah: '-',
                CarapemberianSetelah: data.aturanpakai
            }
            input.value.details.push(newItem);
        }
    } else {
        H.alert('error', 'Terjadi Kesalahan')
    }

    //showModalRiwayatObatPasien.value = false;
}
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

onMounted(() => {
    lockedFormName.value = props.FORM_NAME
    lockedFormUrl.value = props.FORM_URL
    setView()
    triggerAllData()
    getDataExist();
})

</script>

<style lang="scss">
h1 {
    font-weight: bold !important;
}

table {
    width: 100% !important;
    border-collapse: collapse !important;
}

.table-tg th {
    background-color: lightskyblue !important;
    font-weight: bold !important;
    text-align: center !important;
}

.pd {
    padding: 3px !important;
}

.text-bold {
    font-weight: bold;
}

.border {
    border: 1px solid black !important;
}

.center {
    vertical-align: middle !important;
    text-align: center !important;
}
</style>
