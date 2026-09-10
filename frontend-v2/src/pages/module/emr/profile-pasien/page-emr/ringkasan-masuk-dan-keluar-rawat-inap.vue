<template>
 <MasterEMR :isTTD="true" :fieldTTD="'peralihanDPJP'" @simpan="simpan()" :ID_PASIEN="ID_PASIEN" 
    :NOREC_PD="NOREC_PD" :norec_emr="norec_emr" :input="input" :FORM_NAME="props.FORM_NAME" 
    :FORM_URL="props.FORM_URL" :registrasi="props.registrasi" :pasien="props.pasien"
    :COLLECTION="props.COLLECTION" ref="masterRef" :isLoading="isLoading">
    <template #content>
        <VCard>

            <div class="columns is-multiline m-0">
                <div class="column is-4">
                    <h1 style="font-weight: bold">Ruangan:</h1>
                    <VControl>
                        <VInput type="text" class="input" placeholder="Ruangan" v-model="input.ruangan" disabled />
                    </VControl>
                    </div>

                    <div class="column is-4">
                        <h1 style="font-weight: bold">Tanggal</h1>
                        <VField>
                            <VDatePicker v-model="input.tanggal" mode="date" style="width: 100%;" trim-weeks>
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

                    <div class="column is-4">
                        <h1 style="font-weight: bold">Jam</h1>
                        <VDatePicker v-model="input.Jam" mode="time" is24hr>
                            <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:clock" fullwidth>
                                    <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                            </template>
                        </VDatePicker>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-12 mt-5">
                    <VField label="Masalah/Diagnosis Masuk">
                        <div class="column is-3 is-justify-content-left is-align-items-center is-flex pt-0">
                            <VIconButton type="button" raised circle icon="feather:file-text"
                                @click="autoFillEMR('Asesmen Medis RI', '', 'RRK')" :disabled="isLoading"
                                :loading="isLoading" color="primary"
                                v-tooltip-prime.top="'Riwayat Asesmen Medis Rawat Inap'">
                            </VIconButton>
                            <VIconButton type="button" raised circle icon="feather:file-text"
                                @click="autoFillEMR('Asesmen Medis Neonatus', '', 'RRK')" :disabled="isLoading"
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
                        <VTextarea
                            :valueTemplate="input.MasalahDiagnosamasuk"
                            v-model="input.MasalahDiagnosamasuk"
                            rows="4"
                            placeholder=""
                        />
                    </VField>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-10 mt-5">
                    <span><b>Diagnosis Akhir</b></span>
                    <div class="column is-3 is-justify-content-left is-align-items-center is-flex pt-0">
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis RI', '', 'Diagnosa')" :disabled="isLoading"
                            :loading="isLoading" color="primary"
                            v-tooltip-prime.top="'Riwayat Asesmen Medis Rawat Inap'">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis Neonatus', '', 'Diagnosa')" :disabled="isLoading"
                            :loading="isLoading" color="info" v-tooltip-prime.top="'Riwayat Asesmen Medis Neonatus'"
                            class="ml-3">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('Asesmen Medis IGD', '', 'Diagnosa')" :disabled="isLoading"
                            :loading="isLoading" color="danger"
                            v-tooltip-prime.top="'Riwayat Asesmen Medis Gawat Darurat'" class="ml-3">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('CPPT', '', 'Diagnosa')" :disabled="isLoading" :loading="isLoading"
                            color="warning" v-tooltip-prime.top="'Riwayat CPPT'" class="ml-3">
                        </VIconButton>
                        <VIconButton type="button" raised circle icon="feather:file-text"
                            @click="autoFillEMR('CPPT', '', 'DiagnosaSekunder')" :disabled="isLoading" :loading="isLoading"
                            color="success" v-tooltip-prime.top="'Riwayat CPPT (Sekunder)'" class="ml-3">
                        </VIconButton>
                        
                    </div>
                </div>
                <div class="column is-2 mt-5">
                    <span><b>ICD X</b></span>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-2">
                    <span><b>Utama :</b></span>
                </div>
                <div class="column is-6" style="margin-top: -10px;margin-left: -40px;">
                    <VControl>
                        <VTextarea
                            :valueTemplate="input.DiagnosaUtama"
                            v-model="input.DiagnosaUtama"
                            rows="2"
                            placeholder=""
                        />
                        <!-- <VInput type="text" class="input" v-model="input.DiagnosaUtama" /> -->
                    </VControl>
                </div>
                <div class="column is-2"></div>
                <div class="column is-2" style="margin-top: -10px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Kodediagnosautama" />
                    </VControl>
                </div>
                <div class="column is-2">
                    <span><b>Sekunder :</b></span>
                </div>
                <div class="column is-1" style="margin-left: -80px;"><span><b>1.</b></span></div>
                <div class="column is-6" style="margin-top: -10px;margin-left: -40px;">
                    <VControl>
                        <VTextarea
                            :valueTemplate="input.Diagnosasekunder1"
                            v-model="input.Diagnosasekunder1"
                            rows="2"
                            placeholder=""
                        />
                        <!-- <VInput type="text" class="input" v-model="input.Diagnosasekunder1" /> -->
                    </VControl>
                </div>
                <div class="column is-2"></div>
                <div class="column is-2" style="margin-top: -10px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Kodediagnosasekunder1" />
                    </VControl>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-2"></div>
                <div class="column is-1" style="margin-left: -80px;"><span><b>2.</b></span></div>
                <div class="column is-6" style="margin-top: -10px;margin-left: -40px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Diagnosasekunder2" />
                    </VControl>
                </div>
                <div class="column is-2"></div>
                <div class="column is-2" style="margin-top: -10px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Kodediagnosasekunder2" />
                    </VControl>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-2"></div>
                <div class="column is-1" style="margin-left: -80px;"><span><b>3.</b></span></div>
                <div class="column is-6" style="margin-top: -10px;margin-left: -40px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Diagnosasekunder3" />
                    </VControl>
                </div>
                <div class="column is-2"></div>
                <div class="column is-2" style="margin-top: -10px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Kodediagnosasekunder3" />
                    </VControl>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-2"></div>
                <div class="column is-1" style="margin-left: -80px;"><span><b>4.</b></span></div>
                <div class="column is-6" style="margin-top: -10px;margin-left: -40px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Diagnosasekunder4" />
                    </VControl>
                </div>
                <div class="column is-2"></div>
                <div class="column is-2" style="margin-top: -10px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Kodediagnosasekunder4" />
                    </VControl>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-2"></div>
                <div class="column is-1" style="margin-left: -80px;"><span><b>5.</b></span></div>
                <div class="column is-6" style="margin-top: -10px;margin-left: -40px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Diagnosasekunder5" />
                    </VControl>
                </div>
                <div class="column is-2"></div>
                <div class="column is-2" style="margin-top: -10px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Kodediagnosasekunder5" />
                    </VControl>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-2"></div>
                <div class="column is-1" style="margin-left: -80px;"><span><b>6.</b></span></div>
                <div class="column is-6" style="margin-top: -10px;margin-left: -40px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Diagnosasekunder6" />
                    </VControl>
                </div>
                <div class="column is-2"></div>
                <div class="column is-2" style="margin-top: -10px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Kodediagnosasekunder6" />
                    </VControl>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-4">
                    <span><b>Sebab Kekerasan/kecelakaan/keracunan :</b></span>
                </div>
                <div class="column is-4" style="margin-top: -10px;margin-left: -40px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.sebabkekerasan" />
                    </VControl>
                </div>
                <div class="column is-2"></div>
                <div class="column is-2" style="margin-top: -10px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Kodesebabkekerasan" />
                    </VControl>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-2">
                    <span><b>komplikasi :</b></span>
                </div>
                <div class="column is-6" style="margin-top: -10px;margin-left: -40px;">
                    <VField>
                        <VTextarea rows="2" v-model="input.komplikasi"></VTextarea>
                    </VField>
                </div>
                <div class="column is-2"></div>
                <div class="column is-2" style="margin-top: -10px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Kodediagnosakomplikasi" />
                    </VControl>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-2">
                    <span><b>Patologi Anatomi :</b></span>
                </div>
                <div class="column is-6" style="margin-top: -10px;margin-left: -40px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.PA" />
                    </VControl>
                </div>
                <div class="column is-1">
                    <VIconButton class="mr-2" raised circle icon="fas fa-notes-medical" @click="getLabo()"
                      :loading="isLoading" color="success" v-tooltip-prime.top="'Input Laboratorium'">
                    </VIconButton>
                </div>
                <div class="column is-1"></div>
                <div class="column is-2" style="margin-top: -10px;">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.KodePA" />
                    </VControl>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <table border="1">
                    <tr>
                        <td>
                            <div class="columns is-multiline m-0 pb-3">
                                <div class="column is-12" style="text-align: center;"><b>Operasi/Tindakan/Anestesi</b></div>
                            </div>
                            <div class="is-flex m-0 px-2 pb-5" v-for="(OPA, key) in listLaporanOperasi">
                                <div class="mr-3" style="margin-top: 10px;"><span><b>{{ key + 1 }}.</b></span></div>
                                <div class="">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input['OPA'+(key == 0 ? '' : key)]" />
                                    </VControl>
                                </div>
                                
                            </div>
                            <!-- <div class="columns is-multiline m-0">
                                <div class="column is-1"  style="margin-top: 10px;"><span><b>1.</b></span></div>
                                <div class="column is-11">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.OPA" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-1"  style="margin-top: 10px;"><span><b>2.</b></span></div>
                                <div class="column is-11">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.OPA2" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-1"  style="margin-top: 10px;"><span><b>3.</b></span></div>
                                <div class="column is-11">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.OPA3" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-1"  style="margin-top: 10px;"><span><b>4.</b></span></div>
                                <div class="column is-11">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.OPA4" />
                                    </VControl>
                                </div>
                            </div> -->
                        </td>
                        <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="text-align: center;"><b>Tanggal</b></div>
                            </div>
                            <div class="columns is-multiline m-0" v-for="(tabs, index) in listLaporanOperasi">
                                <div class="column is-12 pb-0 mt-0">
                                    <VField>
                                        <VDatePicker v-model="input['tanggalOpa'+(index + 1)]" mode="date" style="width: 100%;" trim-weeks>
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
                            <!-- <div class="columns is-multiline m-0">
                                <div class="column is-12" style="text-align: center;"><b>Tanggal</b></div>
                                <div class="column is-12 pb-0">
                                    <VField>
                                        <VDatePicker v-model="input.tanggalOpa1" mode="date" style="width: 100%;" trim-weeks>
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
                            <div class="columns is-multiline m-0">
                                <div class="column is-12 pb-0">
                                    <VField>
                                        <VDatePicker v-model="input.tanggalOpa2" mode="date" style="width: 100%;" trim-weeks>
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
                            <div class="columns is-multiline m-0">
                                <div class="column is-12 pb-0">
                                    <VField>
                                        <VDatePicker v-model="input.tanggalOpa3" mode="date" style="width: 100%;" trim-weeks>
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
                            <div class="columns is-multiline m-0">
                                <div class="column is-12 pb-0">
                                    <VField>
                                        <VDatePicker v-model="input.tanggalOpa4" mode="date" style="width: 100%;" trim-weeks>
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
                            </div> -->
                        </td>
                        <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="text-align: center;"><b>Jenis</b></div>
                                <div class="column is-12">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.JenisOpa1" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.JenisOpa2" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.JenisOpa3" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.JenisOpa4" />
                                    </VControl>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="text-align: center;"><b>ICD 9-CM</b></div>
                                <div class="column is-12">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cm1" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cm2" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cm3" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cm4" />
                                    </VControl>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="columns is-multiline m-0">
                <table border="1">
                    <tr>
                        <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="text-align: center;"><b>Pemeriksaan Penunjang</b></div>
                                <!-- <VField vertical label="Radiologi" class="px-3">
                                    <VControl>
                                        <VTextarea
                                            :valueTemplate="input.DiagnosaUtama"
                                            v-model="input.DiagnosaUtama"
                                            rows="2"
                                            placeholder=""
                                        />
                                    </VControl>
                                </VField> -->
                                <div class="column is-12 px-3">
                                    <div class="is-flex mb-2" style="justify-content: space-between">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                true-value="Radiologi"
                                                label="Radiologi"
                                                v-model="input.CBRadiologi"
                                            />
                                        </VControl>
                                        <VIconButton type="button" raised circle icon="feather:file-text"
                                            @click="getRadio()" :disabled="isLoading" :loading="isLoading"
                                            color="success" v-tooltip-prime.top="'Riwayat Radiologi'" style="margin-top: -5px">
                                        </VIconButton>
                                    </div>
                                    <VField>
                                        <VControl>
                                            <VTextarea
                                                :valueTemplate="input.TXTRadiologi"
                                                v-model="input.TXTRadiologi"
                                                rows="2"
                                                placeholder=""
                                            />
                                        </VControl>
                                    </VField>
                                </div>
                                <!-- <div class="column is-6" style="margin-top: -10px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="X-Ray"
                                            label="X-Ray"
                                            v-model="input.Xray"
                                        />
                                    </VControl>
                                </div>
                                <div class="column is-6" style="margin-top: -16px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBxray" />
                                    </VControl>
                                </div> -->
                            </div>
                            <div class="column is-12 px-3">
                                <div class="is-flex mb-2" style="justify-content: space-between">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            true-value="Penunjang Khusus"
                                            label="Penunjang Khusus"
                                            v-model="input.CBPenunjangKhusus"
                                        />
                                    </VControl>
                                    <VIconButton type="button" raised circle icon="feather:file-text"
                                        @click="setPenunjang()" :disabled="isLoading" :loading="isLoading"
                                        color="success" v-tooltip-prime.top="'Riwayat Penunjang Khusus'" style="margin-top: -5px">
                                    </VIconButton>
                                </div>
                                <VField>
                                    <VControl>
                                        <VTextarea
                                            :valueTemplate="input.TXTPenunjangKhusus"
                                            v-model="input.TXTPenunjangKhusus"
                                            rows="2"
                                            placeholder=""
                                        />
                                    </VControl>
                                </VField>
                            </div>
                            <!-- <div class="columns is-multiline m-0">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="ECG"
                                            label="ECG"
                                            v-model="input.ECG"
                                        />
                                    </VControl>
                                </div>
                                <div class="column is-6" style="margin-top: -10px;">
                                    <VField addons style="padding: 5px;padding-top:0px"
                                        >
                                        <VControl>
                                            <VInput type="text" class="input"
                                                v-model="input.TBECG" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>X</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                            </div> -->
                            <!-- <div class="columns is-multiline m-0">
                                <div class="column is-7">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Endoscopy"
                                            label="Endoscopy"
                                            v-model="input.Endoscopy"
                                        />
                                    </VControl>
                                </div>
                                <div class="column is-5" style="margin-top: -10px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBEndoscopy" />
                                    </VControl>
                                </div>
                            </div> -->
                        </td>
                        <!-- <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="text-align: center;"><b>Tanggal</b></div>
                                <div class="column is-12" style="margin-top: -16px;">
                                    <VField>
                                        <VDatePicker v-model="input.tanggapenunjang1" mode="date" style="width: 100%;" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.50rem;">
                                                    <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -16px;">
                                    <VField>
                                        <VDatePicker v-model="input.tanggapenunjang2" mode="date" style="width: 100%;" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.50rem;">
                                                    <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -16px;">
                                    <VField>
                                        <VDatePicker v-model="input.tanggapenunjang3" mode="date" style="width: 100%;" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.50rem;">
                                                    <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -16px;">
                                    <VField>
                                        <VDatePicker v-model="input.tanggapenunjang4" mode="date" style="width: 100%;" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.50rem;">
                                                    <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -16px;">
                                    <VField>
                                        <VDatePicker v-model="input.tanggapenunjang5" mode="date" style="width: 100%;" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.50rem;">
                                                    <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                        </td> -->
                        <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="text-align: center;"><b>ICD 9-CM</b></div>
                                <div class="column is-12" style="margin-top: -15px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cmpenunjang1" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -10px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cmpenunjang2" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -10px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cmpenunjang3" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -10px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cmpenunjang4" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -10px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cmpenunjang5" />
                                    </VControl>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="text-align: center;"><b>Pemeriksaan Penunjang</b></div>
                                <div class="column is-12 px-3">
                                    <div class="is-flex mb-2" style="justify-content: space-between">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                true-value="Patologi Klinik"
                                                label="Patologi Klinik"
                                                v-model="input.CBPatalogiKlinik"
                                            />
                                        </VControl>
                                        <VIconButton type="button" raised circle icon="feather:file-text"
                                            @click="getLabo('PK')" :disabled="isLoading" :loading="isLoading"
                                            color="success" v-tooltip-prime.top="'Riwayat Lab PK'" style="margin-top: -5px">
                                        </VIconButton>
                                    </div>
                                    <VField>
                                        <VControl>
                                            <VTextarea
                                                :valueTemplate="input.TXTPatalogiKlinik"
                                                v-model="input.TXTPatalogiKlinik"
                                                rows="2"
                                                placeholder=""
                                            />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12 px-3">
                                    <div class="is-flex mb-2" style="justify-content: space-between">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                true-value="Patologi Anatomi"
                                                label="Patologi Anatomi"
                                                v-model="input.CBPatalogiAnatomi"
                                            />
                                        </VControl>
                                        <VIconButton type="button" raised circle icon="feather:file-text"
                                            @click="getLabo('PA')" :disabled="isLoading" :loading="isLoading"
                                            color="success" v-tooltip-prime.top="'Riwayat Lab PA'" style="margin-top: -5px">
                                        </VIconButton>
                                    </div>
                                    <VField>
                                        <VControl>
                                            <VTextarea
                                                :valueTemplate="input.TXTPatalogiAnatomi"
                                                v-model="input.TXTPatalogiAnatomi"
                                                rows="2"
                                                placeholder=""
                                            />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12 px-3">
                                    <div class="is-flex mb-2" style="justify-content: space-between">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                true-value="Mikrobiologi"
                                                label="Mikrobiologi"
                                                v-model="input.CBMikrobiologi"
                                            />
                                        </VControl>
                                        <VIconButton type="button" raised circle icon="feather:file-text"
                                            @click="getLabo('Mikro')" :disabled="isLoading" :loading="isLoading"
                                            color="success" v-tooltip-prime.top="'Riwayat Lab Mikro'" style="margin-top: -5px">
                                        </VIconButton>
                                    </div>
                                    <VField>
                                        <VControl>
                                            <VTextarea
                                                :valueTemplate="input.TXTMikrobiologi"
                                                v-model="input.TXTMikrobiologi"
                                                rows="2"
                                                placeholder=""
                                            />
                                        </VControl>
                                    </VField>
                                </div>
                                <!-- <div class="column is-12">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Mikrobiologi"
                                            label="Mikrobiologi"
                                            v-model="input.Mikrobiologi"
                                        />
                                    </VControl>
                                </div> -->
                            </div>
                        </td>
                        <!-- <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="text-align: center;"><b>Tanggal</b></div>
                                <div class="column is-12" style="margin-top: -16px;">
                                    <VField>
                                        <VDatePicker v-model="input.tanggapenunjang6" mode="date" style="width: 100%;" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.50rem;">
                                                    <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -16px;">
                                    <VField>
                                        <VDatePicker v-model="input.tanggapenunjang7" mode="date" style="width: 100%;" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.50rem;">
                                                    <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -16px;">
                                    <VField>
                                        <VDatePicker v-model="input.tanggapenunjang8" mode="date" style="width: 100%;" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.50rem;">
                                                    <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -16px;">
                                    <VField>
                                        <VDatePicker v-model="input.tanggapenunjang9" mode="date" style="width: 100%;" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.50rem;">
                                                    <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -16px;">
                                    <VField>
                                        <VDatePicker v-model="input.tanggapenunjang10" mode="date" style="width: 100%;" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.50rem;">
                                                    <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                        </td> -->
                        <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="text-align: center;"><b>ICD 9-CM</b></div>
                                <div class="column is-12" style="margin-top: -15px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cmpenunjang6" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -10px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cmpenunjang7" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -10px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cmpenunjang8" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -10px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cmpenunjang9" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="margin-top: -10px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.Icd9cmpenunjang10" />
                                    </VControl>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="columns is-multiline m-0">
                <table border="1">
                    <tr>
                        <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-10" style="text-align: center;"><b>TgL Masuk</b></div>
                                <div class="column is-10">
                                    <VField>
                                        <VDatePicker v-model="input.tanggalMasuk" mode="datetime" style="width: 100%;" trim-weeks is24hr>
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
                        </td>
                        <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-10" style="text-align: center;"><b>TgL Keluar</b></div>
                                <div class="column is-10">
                                    <VField>
                                        <VDatePicker v-model="input.tanggalKeluar" mode="datetime" style="width: 100%;" trim-weeks is24hr>
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
                        </td>
                        <td rowspan="3">
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="text-align: center;"><b>Keadaan Keluar</b></div>
                                <div class="column is-12" style="margin-top: -10px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Sembuh"
                                            label="Sembuh"
                                            v-model="input.Sembuh"
                                        />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Membaik"
                                            label="Membaik"
                                            v-model="input.Membaik"
                                        />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Belumsembuh"
                                            label="Belum sembuh"
                                            v-model="input.Belumsembuh"
                                        />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Meninggalkurang48jam"
                                            label="Meninggal <48 jam"
                                            v-model="input.Meninggalkurang48jam"
                                        />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Meninggallebih48jam"
                                            label="Meninggal >48 jam"
                                            v-model="input.Meninggallebih48jam"
                                        />
                                    </VControl>
                                </div>
                            </div>
                        </td>
                        <td rowspan="3">
                            <div class="columns is-multiline m-0">
                                <div class="column is-12" style="text-align: center;"><b>Cara Keluar</b></div>
                                <div class="column is-12" style="margin-top: -10px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="DiijinkanPulang"
                                            label="Diijinkan Pulang"
                                            v-model="input.DiijinkanPulang"
                                        />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="PulagPaksa"
                                            label="Pulang Paksa"
                                            v-model="input.PulagPaksa"
                                        />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Lari"
                                            label="Lari"
                                            v-model="input.Lari"
                                        />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-12">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="PindahRDLain"
                                            label="Pindah RD Lain"
                                            v-model="input.PindahRDLain"
                                        />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-7">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Dirujukke"
                                            label="Dirujuk ke:"
                                            v-model="input.Dirujukke"
                                        />
                                    </VControl>
                                </div>
                                <div class="column is-5" style="margin-top: -10px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBDirujukke" />
                                    </VControl>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-5" style="margin-top: 10px;">
                                    <span>Lama Dirawat:</span>
                                </div>
                                <div class="column is-6">
                                    <VField addons style="padding: 5px;padding-top:0px"
                                        >
                                        <VControl>
                                            <VInput type="text" class="input"
                                                v-model="input.TBLamadirawat" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>hr</VButton>
                                        </VControl>
                                    </VField>
                                </div> 
                            </div>
                        </td>
                        <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-4"  style="margin-top: 10px;">
                                    <span>BB Lahir:</span>
                                </div>
                                <div class="column is-6">
                                    <VField addons style="padding: 5px;padding-top:0px"
                                        >
                                        <VControl>
                                            <VInput type="text" class="input"
                                                v-model="input.BBLahir" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>gr</VButton>
                                        </VControl>
                                    </VField>
                                </div> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="columns is-multiline m-0">
                                <div class="column is-3">
                                    <span>Transfusi Darah :</span>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="TransfusiDarahTidak"
                                            label="Tidak"
                                            v-model="input.TransfusiDarahTidak"
                                        />
                                    </VControl>
                                </div> 
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="TransfusiDarahYa"
                                            label="Ya"
                                            v-model="input.TransfusiDarahYa"
                                        />
                                    </VControl>
                                </div> 
                                <div class="column is-3" style="margin-top: 10px;">
                                        <h1>Golongan Darah :</h1>
                                </div>
                                <div class="column is-2" style="margin-left: -30px;">
                                    <Multiselect v-model="input.Goldar" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                        :options="d_Goldar" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-6"></div>
                                <div class="column is-2">
                                        <h1>Rhesus :</h1>
                                </div>
                                <div class="column is-3" style="margin-top: -10px;margin-left: -30px;">
                                    <Multiselect v-model="input.Rhesus" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                        :options="d_Rhesus" :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="columns is-multiline m-0">
                                <div class="column is-3">
                                    <span>Transfusi Albumin :</span>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="TransfusiAlbuminTidak"
                                            label="Tidak"
                                            v-model="input.TransfusiAlbuminTidak"
                                        />
                                    </VControl>
                                </div> 
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="TransfusiAlbumiYa"
                                            label="Ya"
                                            v-model="input.TransfusiAlbumiYa"
                                        />
                                    </VControl>
                                </div>  
                            </div> 
                        </td>
                        <td rowspan="3" colspan="2">
                            <div class="column is-12">
                                <VField label="Garut">
                                    <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <div class="column" style="text-align:center;">
                                    <h1>Tanda tangan dan nama dokter</h1>
                                    <!-- <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" /> -->
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.CBDokter" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" class="mt-2" />
                                    </VControl>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="columns is-multiline m-0">
                                <div class="column is-3">
                                    <span>Radioterapi :</span>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="RadioterapiTidak"
                                            label="Tidak"
                                            v-model="input.RadioterapiTidak"
                                        />
                                    </VControl>
                                </div> 
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="RadioterapiYa"
                                            label="Ya"
                                            v-model="input.RadioterapiYa"
                                        />
                                    </VControl>
                                </div>  
                            </div> 
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="columns is-multiline m-0">
                                <div class="column is-3">
                                    <span>InfeksiNosokomial :</span>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="InfeksiNosokomialTidak"
                                            label="Tidak"
                                            v-model="input.InfeksiNosokomialTidak"
                                        />
                                    </VControl>
                                </div> 
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="InfeksiNosokomialYa"
                                            label="Ya"
                                            v-model="input.InfeksiNosokomialYa"
                                        />
                                    </VControl>
                                </div>  
                            </div> 
                            <div class="columns is-multiline m-0">
                                <div class="column is-4">
                                    <span><b>Penyebab Infeksi :</b></span>
                                </div>
                                <div class="column is-6" style="margin-top: -10px;margin-left: -40px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.PenyebabInfeksi" />
                                    </VControl>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <hr style="background-color: red;">
            <div class="column is-12" style="text-align: center;">
                <span><b>SEBAB KEMATIAN</b></span><br>
                <span><b>(Untuk Umur 7 hari keatas)</b></span>
            </div>
             <div class="column is-12">
                <table border="1">
                    <tr>
                        <td>
                            <div class="column is-12" style="text-align: center;">
                                <span><b>I</b></span>
                            </div>
                        </td>
                        <td>
                            <div class="column is-12" style="text-align: center;">
                                <span><b>Penyebab Kematian</b></span>
                            </div> 
                        </td>
                        <td>
                            <div class="column is-12" style="text-align: center;">
                                <span><b>Lamanya Mulai sakit hingga meninggal dunia</b></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="column is-12" style="margin-top: 10px;">
                                <span>a. Penyakit atau keadaan yang langsung mengakibatkan kematian</span>
                            </div>
                            <div class="column is-12" style="margin-top: 150px;">
                                <span>b. c. Penyakit (bila ada) yang menjadi lantaran timbulnya sebab kematian tsb. Pada,dengan menyebut penyakit yang menjadi pokok pangkal terakhir</span>
                            </div>
                        </td>
                        <td>
                            <div class="columns is-multiline m-0">
                                <div class="column is-2" style="margin-top: 10px;">
                                    <span>a.</span>
                                </div>
                                <div class="column is-10">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.PenyebabKematianA" />
                                    </VControl>
                                </div>
                                <div class="column is-12">
                                    <span>Penyakit tsb. Dalam ruang a, disebabkan oleh<br>
                                        (akibat dari):</span>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VTextarea rows="2" v-model="input.akibatdari1"></VTextarea>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-2">
                                    <span>b.</span>
                                </div>
                                <div class="column is-10" style="margin-top: -10px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.PenyebabKematianB" />
                                    </VControl>
                                </div>
                                <div class="column is-12">
                                    <span>Penyakit tsb. Dalam ruang b, disebabkan oleh<br>
                                        (akibat dari):</span>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VTextarea rows="2" v-model="input.akibatdari2"></VTextarea>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline m-0">
                                <div class="column is-2">
                                    <span>c.</span>
                                </div>
                                <div class="column is-10" style="margin-top: -10px;">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.PenyebabKematianC" />
                                    </VControl>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="column is-12" >
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.lamasakit1" />
                                </VControl>
                            </div> 
                            <div class="column is-12" style="margin-top: 140px;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.lamasakit2" />
                                </VControl>
                            </div> 
                            <div class="column is-12" style="margin-top: 130px;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.lamasakit3" />
                                </VControl>
                            </div> 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="column is-12" style="text-align: center;">
                                <span><b>II</b></span>
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="column is-12">
                                <span>Penyakit-penyakit lain yang berarti dan mempengaruhi pula kematian itu, tetapi tidak ada hubungannya dengan penyakit-penyakit tersebut dalam 1a,b,c.</span>
                            </div> 
                        </td>
                        <td>
                            <div class="column is-12">
                                <span>Disamping penyakit-penyakit tersebut di atas<br>
                                    terdapat pula penyakit:</span>
                            </div> 
                            <div class="column is-10" style="margin-top: -10px;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.PenyebabKematianII" />
                                </VControl>
                            </div>
                            <div class="column is-10" style="margin-top: -10px;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.PenyebabKematianIII" />
                                </VControl>
                            </div>
                        </td>
                        <td>
                            <div class="column is-12" style="margin-top: 50px;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.lamasakit4" />
                                </VControl>
                            </div>
                            <div class="column is-12" style="margin-top: -10px;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.lamasakit5" />
                                </VControl>
                            </div>
                        </td>
                    </tr>
                </table>
             </div>
            </VCard>
            <VCard>
                <div class="column is-12" style="text-align: center;">
                    <span><b>Sebab Kematian bayi umur 0-6 hari</b></span>
                </div>
                <div class="column is-12">
                    <span>a. Penyakit Utama atau kondidi pada janin atau bayi:</span>
                    <VField>
                        <VTextarea rows="2" v-model="input.PenyakitUtamaBayi"></VTextarea>
                    </VField>
                </div>
                <div class="column is-12">
                    <span>b. Penyakit lain ibu atau kondisi yang berpengaruh:</span>
                    <VField>
                        <VTextarea rows="2" v-model="input.PenyakitlainBayi"></VTextarea>
                    </VField>
                </div>
                <div class="column is-12">
                    <span>c. Penyakit Utama ibu atau kondidi yang berpengaruh pada janin atau bayi:</span>
                    <VField>
                        <VTextarea rows="2" v-model="input.PenyakitUtamaIbu"></VTextarea>
                    </VField>
                </div>
                <div class="column is-12">
                    <span>d. Penyakit lain ibu atau kondisi yang berpengaruh pada janin atau bayi:</span>
                    <VField>
                        <VTextarea rows="2" v-model="input.PenyakitlainIbu"></VTextarea>
                    </VField>
                </div>
                <div class="column is-12">
                    <span>e. Keadaan-keadaan lain yang berpengaruh :</span>
                    <VField>
                        <VTextarea rows="2" v-model="input.keadaanlain"></VTextarea>
                    </VField>
                </div>
                <div class="columns is-multiline m-0">
                    <div class="column is-8"></div>
                    <div class="column is-4">
                        <VField label="Garut">
                            <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <div class="column is-8"></div>
                    <div class="column is-4">
                        <div class="column" style="text-align:center;">
                            <h1>Yang memberin keterangan sebab kematian</h1>
                            <!-- <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" /> -->
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.CBPetugas" :suggestions="d_Petugas"
                                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                    :field="'label'" class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                </div>
            </VCard>
    </template>
 </MasterEMR>

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
</template>

<script setup lang="ts">
import  MasterEMR from './master-emr.vue'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as H from '/@src/utils/appHelper'
import moment from 'moment'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const masterRef = ref(null)
const dataPasien = '';
const d_Dokter: any = ref([]);
const d_Petugas: any = ref([]);
const d_Goldar: any = ref([{ value: 1, label: 'A' }, { value: 2, label: 'B' }, { value: 3, label: 'AB' }, { value: 4, label: 'O' }])
const d_Rhesus: any = ref([{ value: 1, label: 'Posistif' }, { value: 2, label: 'Negatif' }])
const dataTTD: any = ref([])
const NOREC_EMRPASIEN: any = ref('')
const isLoading: any = ref(false);
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const isBayiMeninggal = ref(false);
const meninggal7Hari = ref(false);
const showModalAsmedRI: any = ref(false);
const listAsmedRI: any = ref([])
const listLaporanOperasi: any = ref([]);
listLaporanOperasi.value = Array(4).fill(undefined)

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
    }>(),
    {
      pasien: {},
      registrasi: {},
      FORM_NAME: '',
      FORM_URL: '',
      COLLECTION: '',
    }
  )
const input: any = ref({
    detail: [
        {
            no: 1,
            value: ''
        }
    ],
    rawatbersama: [
        {
            no: 1,
            dokter: '',
            tglAwal: '',
            tglAkhir: ''
        }
    ],
    peralihanDPJP: [
        {
            no: 1,
            dokter: '',
            ttd: ''
        }
    ]
})

const item: any = reactive({

NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
NOREC_APD: props.registrasi.norec_apd,
RUANGAN_LAST: props.registrasi.objectruanganlastfk,
DEPARTEMEN_FK: props.registrasi.objectdepartemenfk,
registrasi: {
  ruanganfk: props.registrasi.objectruanganlastfk,
  departemenfk: props.registrasi.objectdepartemenfk,
}

})


const fetchDokter = async (filter: any) => {
    d_Dokter.value = await H.fetchDokter(filter);
}

const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
}

const getLabo = (type = '') => {
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
            hasilLab += '# ' + detail.namaproduk + ' - ' + detail.tglorder + ' ';
          }
        }
      }
      switch (type) {
        case 'PK':
            if (input.value.TXTPatalogiKlinik != undefined || input.value.TXTPatalogiKlinik != null) {
                input.value.TXTPatalogiKlinik += '\n' + hasilLab;
            } else {
                input.value.TXTPatalogiKlinik = hasilLab;
            }
            break;
        case 'PA':
            if (input.value.TXTPatalogiAnatomi != undefined || input.value.TXTPatalogiAnatomi != null) {
                input.value.TXTPatalogiAnatomi += '\n' + hasilLab;
            } else {
                input.value.TXTPatalogiAnatomi = hasilLab;
            }
            break;

        case 'Mikro':
            if (input.value.TXTMikrobiologi != undefined || input.value.TXTMikrobiologi != null) {
                input.value.TXTMikrobiologi += '\n' + hasilLab;
            } else {
                input.value.TXTMikrobiologi = hasilLab;
            }
            break;
        default:
            if (input.value.PA != undefined || input.value.PA != null) {
                input.value.PA += '\nLaboratorium : ' + hasilLab;
            } else {
                input.value.PA = 'Laboratorium : ' + hasilLab;
            }
            break;
      }
      H.alert('success', 'Berhasil ditambahkan')
    } else {
      H.alert('warning', 'Belum ada riwayat')
    }
    isLoading.value = false
  })
}


const setAutoFill = async () => {
    input.value.ruangan = props.registrasi.namaruangan
    input.value.tanggalMasuk = props.registrasi.tglsep
    input.value.tanggal = new Date()
    input.value.Jam = new Date()
    input.value.tanggalKeluar = props.registrasi.tglpulang
    const response_AsmedRajal = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenMedisRawatJalan" + `&field=TADiagnosa`)
    const response_RingkasanKeluar = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=RingkasanKeluar" + `&field=TADiagnosisPrimer`)
    const response_CPPT = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=CatatanPerkembanganPasienTerintegrasi" + `&field=riwayatkeluar,statuskeluar,tujuan_skrs`)
    const response_VS = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=VitalSign" + `&field=beratBadan`)
    const response_LO = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=laporanOperasi" + `&field=namaOperasi,tglOperasi`)
    // const responseL
    if (response_AsmedRajal != null) {
        input.value.MasalahDiagnosamasuk = response_AsmedRajal.TADiagnosa;
        input.value.sebabkekerasan = response_AsmedRajal.MOI;
    }
    if (response_RingkasanKeluar != null) {
        input.value.DiagnosaUtama = response_RingkasanKeluar.TADiagnosisPrimer;
    }
    if(response_CPPT != null) {
        if(response_CPPT.riwayatkeluar == 'Meninggal <= 48 Jam') {
            input.value.Meninggalkurang48jam = true;
            isBayiMeninggal.value = true;
            meninggal7Hari.value = true;
        }else if (response_CPPT.riwayatkeluar == 'Meninggal >= 48 Jam' ) {
            input.value.Meninggallebih48jam = true;
            isBayiMeninggal.value = true
            meninggal7Hari.value = true
        }else if (response_CPPT.riwayatkeluar == 'DOA') {
            isBayiMeninggal.value = true;
            meninggal7Hari.value = true
        }else if (response_CPPT.riwayatkeluar == 'Sembuh') {
            input.value.Sembuh = true;
        }else if (response_CPPT.riwayatkeluar == 'Membaik') {
            input.value.Membaik = true;
        }else if (response_CPPT.riwayatkeluar == 'Belum Sembuh') {
            input.value.Belumsembuh = true
        }

        if(response_CPPT.statuskeluar == 'Pulang Paksa') {
            input.value.PulagPaksa = true;
        }else if(response_CPPT.statuskeluar == 'Diijinkan Pulang') {
            input.value.DiijinkanPulang = true;
        }else if(response_CPPT.statuskeluar == 'Dirujuk') {
            input.value.Dirujukke = true;
            input.value.TBDirujukke = response_CPPT.tujuan_skrs
        }
    }

    if(response_VS != null) {
        input.value.BBLahir = isNaN(parseInt(response_VS.beratBadan)) != true ? parseInt(response_VS.beratBadan) * 1000 : ''; 
    }

    // let mn = '2006-01-03';
    if(props.registrasi?.tglmeninggal != null) {
        let tgllahir = new Date(props.pasien?.tgllahir).getTime();
        let tglmeninggal = new Date(props.registrasi?.tglmeninggal).getTime();
        let diff = parseInt((tglmeninggal-tgllahir)/(24*3600*1000));
        if(diff > 7) {
            meninggal7Hari.value = true;
        }
    }

    if(response_LO != null) {
        listLaporanOperasi.value = response_LO.namaOperasi;
        for (let index = 0; index < response_LO.namaOperasi.length; index++) {
            const element = response_LO.namaOperasi[index];
            const keyToUse = 'OPA' + (index == 0 ? '' : index);
            const keyToUseTanggal = 'tanggalOpa' + (index == 0 ? "1" : `${index + 1}`);
            input.value[keyToUse] = element;
            input.value[keyToUseTanggal] = new Date(response_LO.tglOperasi);
        }
        console.log('input all', input.value)
    }else {
        listLaporanOperasi.value = Array(4).fill(undefined);
        for (let index = 0; index < listLaporanOperasi.value.length; index++) {
            const element = listLaporanOperasi.value[index];
            const keyToUse = 'OPA' + (index == 0 ? '' : index);
            input.value[keyToUse] = element;
        }
    }

    let tglmsk = new Date(props.registrasi.tglregistrasi);
    let tglpulang = props.registrasi.tglpulang != null ? new Date(props.registrasi.tglpulang) : '';
    let lamarawat = parseInt(((tglpulang == '' ? new Date().getTime() : tglpulang.getTime()) - tglmsk.getTime()) / (24*3600*1000));
    input.value.TBLamadirawat = lamarawat;
};


const simpan = () => {
    if (!input.value.MasalahDiagnosamasuk) {
    H.alert('warning', 'Masalah/Diagnosis masuk wajib diisi');
    return;
  }
    if (!input.value.DiagnosaUtama) {
    H.alert('warning', 'Diagnosa utama wajib diisi');
    return;
  }
//     if (!input.value.Kodediagnosautama) {
//     H.alert('warning', ' Kode diagnosa utama wajib diisi');
//     return;
//   }
    if (!input.value.Diagnosasekunder1) {
    H.alert('warning', 'Diagnosa sekunder 1 wajib diisi');
    return;
  }

  if(((input.value.Meninggalkurang48jam == true || input.value.Meninggallebih48jam == true) && !input.value.BBLahir) || meninggal7Hari.value == true){
    if (!input.value.PenyebabKematianA) {
    H.alert('warning', 'Penyebab Kematian A wajib diisi');
    return;
    }

    if (!input.value.akibatdari1) {
    H.alert('warning', 'Akibat Dari Penyebab Kematian A wajib diisi');
    return;
    }

    if (!input.value.PenyebabKematianB) {
    H.alert('warning', 'Penyebab Kematian B wajib diisi');
    return;
    }

    if (!input.value.akibatdari2) {
    H.alert('warning', 'Akibat Dari Penyebab Kematian B wajib diisi');
    return;
    }

    if (!input.value.PenyebabKematianC) {
    H.alert('warning', 'Penyebab Kematian C wajib diisi');
    return;
    }

    if (!input.value.lamasakit1) {
    H.alert('warning', 'Lama Sakit A wajib diisi');
    return;
    }

    if (!input.value.lamasakit2) {
    H.alert('warning', 'Lama Sakit A wajib diisi');
    return;
    }

    if (!input.value.lamasakit3) {
    H.alert('warning', 'Lama Sakit A wajib diisi');
    return;
    }

    if (!input.value.PenyebabKematianII) {
    H.alert('warning', 'Penyebab Kematian Lain I wajib diisi');
    return;
    }

    if (!input.value.PenyebabKematianIII) {
    H.alert('warning', 'Penyebab Kematian Lain II wajib diisi');
    return;
    }

    if (!input.value.lamasakit4) {
    H.alert('warning', 'Lama Sakit Penyebab Kematian Lain I wajib diisi');
    return;
    }

    if (!input.value.lamasakit5) {
    H.alert('warning', 'Lama Sakit Penyebab Kematian Lain II wajib diisi');
    return;
    }
  }

  if(((input.value.Meninggalkurang48jam == true || input.value.Meninggallebih48jam == true) && input.value.BBLahir) || isBayiMeninggal.value == true){
    if (!input.value.PenyakitUtamaBayi) {
    H.alert('warning', 'Penyakit Utama Bayi wajib diisi');
    return;
    }

    if (!input.value.PenyakitlainBayi) {
    H.alert('warning', 'Penyakit Lain Bayi wajib diisi');
    return;
    }

    if (!input.value.PenyakitUtamaIbu) {
    H.alert('warning', 'Penyakit Utama Ibu wajib diisi');
    return;
    }

    if (!input.value.PenyakitlainIbu) {
    H.alert('warning', 'Penyakit Lain Ibu wajib diisi');
    return;
    }

    if (!input.value.keadaanlain) {
    H.alert('warning', 'Keadaan Lain Bayi wajib diisi');
    return;
    }
  }

//     if (!input.value.Diagnosasekunder2) {
//     H.alert('warning', 'Diagnosa sekunder 2 wajib diisi');
//     return;
//   }
//     if (!input.value.Diagnosasekunder3) {
//     H.alert('warning', 'Diagnosa sekunder 3 wajib diisi');
//     return;
//   }
//     if (!input.value.Diagnosasekunder4) {
//     H.alert('warning', 'Diagnosa sekunder 4 wajib diisi');
//     return;
//   }
//     if (!input.value.Diagnosasekunder5) {
//     H.alert('warning', 'Diagnosa sekunder 5 wajib diisi');
//     return;
//   }
//     if (!input.value.Diagnosasekunder6) {
//     H.alert('warning', 'Diagnosa sekunder 6 wajib diisi');
//     return;
//   }
//     if (!input.value.sebabkekerasan) {
//     H.alert('warning', 'Sebab Kekerasan/kecelakaan/keracunan wajib diisi');
//     return;
//   }
//     if (!input.value.Kodesebabkekerasan) {
//     H.alert('warning', 'Sebab Kekerasan/kecelakaan/keracunan wajib diisi');
//     return;
//   }
//     if (!input.value.komplikasi) {
//     H.alert('warning', 'Kode diagnosa komplikasi wajib diisi');
//     return;
//   }
//     if (!input.value.Kodediagnosakomplikasi) {
//     H.alert('warning', 'Kode diagnosa komplikasi wajib diisi');
//     return;
//   }
//     if (!input.value.PatalogiAnatomi) {
//     H.alert('warning', 'Patologi Anatomi wajib diisi');
//     return;
//   }
//     if (!input.value.KodePA) {
//     H.alert('warning', 'Kode diagnosa Patalogi Anatomi wajib diisi');
//     return;
//   }
//     if (!input.value.OPA) {
//     H.alert('warning', 'Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.OPA2) {
//     H.alert('warning', 'Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.OPA3) {
//     H.alert('warning', 'Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.OPA4) {
//     H.alert('warning', 'Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.tanggalOpa1) {
//     H.alert('warning', 'Tanggal Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.tanggalOpa2) {
//     H.alert('warning', 'Tanggal Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.tanggalOpa3) {
//     H.alert('warning', 'Tanggal Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.tanggalOpa4) {
//     H.alert('warning', 'Tanggal Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.JenisOpa1) {
//     H.alert('warning', 'Jenis Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.JenisOpa2) {
//     H.alert('warning', 'Jenis Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.JenisOpa3) {
//     H.alert('warning', 'Jenis Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.JenisOpa4) {
//     H.alert('warning', 'Jenis Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.Icd9cm1) {
//     H.alert('warning', 'ICD 9-CM 1 Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.Icd9cm2) {
//     H.alert('warning', 'ICD 9-CM 2 Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.Icd9cm3) {
//     H.alert('warning', 'ICD 9-CM 3 Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//     if (!input.value.Icd9cm4) {
//     H.alert('warning', 'ICD 9-CM 4 Operasi/Tindakan/Anestesi wajib diisi');
//     return;
//   }
//   if (!input.value.X-Ray) {
//   H.alert('warning', 'X-Ray wajib diisi');
//   return;
// }
//   if (!input.value.TBxray) {
//   H.alert('warning', 'X-Ray wajib diisi');
//   return;
// }
//   if (!input.value.CT-Scan) {
//   H.alert('warning', ' wajib diisi');
//   return;
// }
//   if (!input.value.TBxray) {
//   H.alert('warning', 'X-Ray wajib diisi');
//   return;
// }
  
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    console.log("Pasien",props.pasien);
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
        `/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        NOREC_EMRPASIEN.value = response.norec_emr
        input.value.id = response.id
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const simpanTemplate = () => {
    if(!input.value.namatemplate) {
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
    input.value.namatemplate =  null
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const addNewItem = () => {
  input.value.detail.push({
    no: input.value.detail[input.value.detail.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.detail.splice(index, 1)
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
    let ss = await masterRef.value.loadRiwayat()
    if (ss != null) {
        input.value = ss
    }
  }
}

const switchRiwayat: any = ref('')
const switchInputan: any = ref('')
const inputanDiagnosa: any = ref('')
const listRiwayatCPPT: any = ref([])
const showModalCPPT: any = ref(false);
const showModalRiwayatCPPT: any = ref(false);
const listCPPT: any = ref([])

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
                case 'RRK':
                    if (!d.MasalahDiagnosamasuk) { d.MasalahDiagnosamasuk = text; }
                    else { d.MasalahDiagnosamasuk = d.MasalahDiagnosamasuk.trim() + '\n' + text + '\n'; }
                    break;
                case 'Diagnosa':
                    if (!d.DiagnosaUtama) { d.DiagnosaUtama = text; }
                    else { d.DiagnosaUtama = d.DiagnosaUtama.trim() + '\n' + text + '\n'; }
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

const getRadio = () => {
  isLoading.value = true
  // let stringLabo = 'Labora';
  let uri = `radiologi/layanan-radiologi?norec_pd=${item.NOREC_PD}`;

  useApi().get(uri).then((res) => {
    let hasilRadio = '';
    if (res && res.detail.length > 0) {
      for (let index = 0; index < res.detail.length; index++) {
        const group = res.detail[index];
        if (group.details.length > 0) {
          for (let i = 0; i < group.details.length; i++) {
            const detail = group.details[i];
            if (detail.namaproduk != null) {
              hasilRadio += '# ' + detail.namaproduk + ' - ' + detail.tglpelayanan + '\n';
            }
          }
        }
      }
      if (input.value.TXTRadiologi != undefined || input.value.TXTRadiologi != null) {
        input.value.TXTRadiologi += '\n' + hasilRadio;
      } else {
        input.value.TXTRadiologi = hasilRadio;
      }
      H.alert('success', 'Berhasil ditambahkan')
    } else {
      H.alert('warning', 'Belum ada riwayat')
    }
    isLoading.value = false
  }).catch(error => {
    console.log("Err", error)
  })
}

const setPenunjang = () => {
  isLoading.value = true;
  let str = ''
  let gcol = `PemeriksaanKardiotokografi,PemeriksaanObstetri,PemeriksaanGynekologi,PemeriksaanFetal`;
  useApi().get(`emr/get-penunjang-khusus?tables=${gcol}&norec_pd=${NOREC_PD}&nocmfk=${ID_PASIEN}`).then((dt) => {
    isLoading.value = false;
    if (dt.length > 0) {
      // const val = dt[0];
      for (let kObject = 0; kObject < dt.length; kObject++) {
        const val = dt[kObject];
        if (val.table == 'PemeriksaanKardiotokografi') {
          str += val.jenisPemeriksaanObgyn ? `# ${val.jenisPemeriksaanObgyn} - ${moment(val.tanggal).format('YYYY-MM-DD')}\n` : ''
        } else if (val.table == 'PemeriksaanObstetri' || val.table == 'PemeriksaanFetal') {
          str += val.jenisPemeriksaanObgyn ? `# ${val.jenisPemeriksaanObgyn} - ${moment(val.tanggal).format('YYYY-MM-DD')}\n` : ''
        } else if (val.table == 'PemeriksaanGynekologi') {
          str += val.jenisPemeriksaanObgyn ? `# ${val.jenisPemeriksaanObgyn} - ${moment(val.tanggal).format('YYYY-MM-DD')}\n` : ''
        }
      }

      if (str != '') {
        if (input.value.TXTPenunjangKhusus == undefined) {
          input.value.TXTPenunjangKhusus = ''
          input.value.TXTPenunjangKhusus += str;
        } else {
          input.value.TXTPenunjangKhusus += '\n' + str
        }

        H.alert('success', 'Berhasil ambil data')
      } else {
        H.alert('warning', 'Penunjang Khusus belum ada')
      }
    }
  });
}

const addRiwayatCPPT = (data: any) => {
    let d = input.value;
    // Supaya spasinya tidak berjauhan
    ['A', 'S', 'O', 'P'].forEach(key => {
        data[key] = data[key].trim();
    });

    switch (switchRiwayat.value) {
        case 'RRK':
            if (!d.MasalahDiagnosamasuk) {
                d.MasalahDiagnosamasuk = data.A;
            } else {
                d.MasalahDiagnosamasuk = d.MasalahDiagnosamasuk.trim() + '\n' + data.A + '\n';
            }
            break;
        case 'Diagnosa':
            if (!d.DiagnosaUtama) {
                d.DiagnosaUtama = data.A;
            } else {
                d.DiagnosaUtama = d.DiagnosaUtama.trim() + '\n' + data.A + '\n';
            }
            break;
        case 'DiagnosaSekunder':
            if (!d.Diagnosasekunder1) {
                d.Diagnosasekunder1 = data.A;
            } else {
                d.Diagnosasekunder1 = d.Diagnosasekunder1.trim() + '\n' + data.A + '\n';
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
            if (!d.MasalahDiagnosamasuk) {
                d.MasalahDiagnosamasuk = text;
            } else {
                d.MasalahDiagnosamasuk = d.MasalahDiagnosamasuk.trim() + '\n' + text + '\n';
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


onMounted(() => {
  triggerAllData()
  setView()
  fetchDokter();
  setAutoFill();
})


</script>

<style lang="scss">
.text-bold {
    font-weight: bold;
}
</style>
