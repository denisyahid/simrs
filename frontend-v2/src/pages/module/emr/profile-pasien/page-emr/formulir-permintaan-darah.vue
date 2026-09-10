<template>
    <!-- <MasterEMR :isTTD="true" @simpan="simpan()" :ID_PASIEN="props.ID_PASIEN" :FORM_NAME="props.FORM_NAME"
        :NOREC_PD="props.norec_pd" :norec_emr="norec_emr" :input="input" :FORM_URL="props.FORM_URL"
        :registrasi="props.registrasi" :pasien="props.pasien" :COLLECTION="props.COLLECTION" ref="masterRef"
        :isLoading="isLoading" :isHideTemplate="true">
        <template #content> -->
            <VCard class="columns is-multiline">
                <div class="column is-6">
                    <VField label="Tanggal" class="is-rounded-select is-autocomplete-select">
                        <VDatePicker v-model="input.tanggal" mode="datetime" style="width: 100%;" trim-weeks is24hr>
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
                <div class="column is-6" v-if="props.isDashLab == true">
                    <VField label="Pegawai Penerima" class="is-rounded-select is-autocomplete-select">
                        <VControl icon="fa:user" class="prime-auto-cus">
                            <AutoComplete v-model="input.pegawaimenerimafk" :suggestions="d_Pegawai"
                                :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Pegawai Penerima" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField label="Cara Bayar :">
                        <VControl>
                            <VRadio v-model="input.carabayar" value="BPJS" label="BPJS" name="carabayar" color="primary"
                                square />
                            <VRadio v-model="input.carabayar" value="JKBM" label="JKBM" name="carabayar" color="primary"
                                square />
                            <VRadio v-model="input.carabayar" value="IKS" label="IKS" name="carabayar" color="primary"
                                square />
                            <VRadio v-model="input.carabayar" value="Umum" label="Umum" name="carabayar" color="primary"
                                square />
                            <VRadio v-model="input.carabayar" value="Lainnya" label="Lainnya" name="carabayar"
                                color="primary" square />
                            <VInput v-model="input.statusdetail" type="text" style="width: auto !important;"
                                v-if="input.carabayar && input.carabayar == 'Lainnya'" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField label="No. RM">
                        <VControl>
                            <VInput type="text" v-model="input.nocm" :value="props.pasien.nocm" disabled="true" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField label="Nama Pasien">
                        <VControl>
                            <VInput type="text" v-model="input.namapasien" :value="props.pasien.namapasien"
                                disabled="true" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <VField label="Tanggal Lahir">
                                <VControl>
                                    <VInput type="text" v-model="input.tgllahir" :value="props.pasien.tgllahir"
                                        disabled="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Jenis Kelamin">
                                <VControl>
                                    <VInput type="text" v-model="input.jeniskelamin" :value="props.pasien.jeniskelamin"
                                        disabled="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Umur Saat Datang">
                                <VControl>
                                    <VInput type="text" v-model="input.umur" :value="props.pasien.umur"
                                        disabled="true" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <VField label="Alamat">
                        <VControl>
                            <VInput type="text" v-model="input.alamat" :value="props.pasien.alamatlengkap"
                                disabled="true" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField label="Rumah Sakit">
                        <VControl>
                            <VInput type="text"  v-model="input.rumahsakit" value="RUMAH SAKIT BALI MANDARA"/>
                        </VControl>
                    </VField>
                </div>
                <!-- <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <VField label="Bagian">
                                <VControl>
                                    <VInput type="text" v-model="input.bagian"/>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Kelas">
                                <VControl>
                                    <VInput type="text" v-model="input.kelasBagian"/>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div> -->
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <VField label="Ruangan">
                                <VControl>
                                    <VInput type="text" v-model="input.ruangan"/>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Kelas">
                                <VControl>
                                    <VInput type="text" v-model="input.kelasRuangan"/>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <VField label="Dokter yang Meminta">
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.dokterpeminta"
                                :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Cari Dokter...." />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField label="Tanggal Permintaan" class="is-rounded-select is-autocomplete-select">
                        <VDatePicker v-model="input.tglPermintaan" mode="datetime" style="width: 100%;" trim-weeks is24hr>
                            <template #default="{ inputValue, inputEvents }">
                                <VField style="margin-bottom: 0.70rem;">
                                    <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField label="Diagnosa">
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.diagnosa"
                                :suggestions="d_JenisDiagnosa" @complete="fetchDiagnosa($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField label="Alasan Transfusi">
                        <VControl>
                            <VInput type="text" v-model="input.alasan"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField label="Kadar Hemoglobin">
                        <VControl>
                            <VInput type="number" v-model="input.kadarhemoglobin"/>
                        </VControl>
                    </VField>
                </div>
                <!-- <div class="column is-12">
                    <hr>
                   <h1 style="font-weight: bold; font-size: 18px;">Kadar Hemoglobin</h1>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <VField label="Reaksi Transfusi :">
                                <VControl>
                                    <VRadio v-model="input.reaksitransfusi" value="Ya" label="Ya" name="reaksitransfusi" color="primary" square />
                                    <VRadio v-model="input.reaksitransfusi" value="Tidak" label="Tidak" name="reaksitransfusi"
                                        color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-8">
                            <VField label="Gejala - Gejala">
                                <VControl>
                                    <VInput type="text" v-model="input.gejala"/>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div> -->
                <div class="column is-12">
                    <hr>
                    <h1 style="font-weight: bold; font-size: 18px;">Khusus Wanita</h1>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <VField label="Jumlah Kehamilan :">
                                <VControl>
                                    <VInput type="number" v-model="input.jumlahkehamilan"/>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Pernah Abortus ?">
                                <VControl>
                                    <VRadio v-model="input.pernahaborsi" value="Ya" label="Ya" name="pernahaborsi" color="primary" square />
                                    <VRadio v-model="input.pernahaborsi" value="Tidak" label="Tidak" name="pernahaborsi"
                                        color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <hr>
                    <!-- <h1 style="font-weight: bold; font-size: 18px;">Khusus Wanita</h1> -->
                </div>
                <div class="column is-12">
                    <VField label="Jenis Permintaan Darah">
                        <VControl>
                            <VRadio v-model="input.jenismintaDarah" value="Biasa" label="Biasa" name="jenismintaDarah" color="primary"
                                square />
                            <VInput v-model="input.jenisMintaDarahKet" type="text" style="width: auto !important;"
                            v-if="input.jenismintaDarah && input.jenismintaDarah == 'Biasa'" />

                            <VRadio v-model="input.jenismintaDarah" value="Cadangan" label="Cadangan" name="jenismintaDarah" color="primary"
                                square />
                            <VInput v-model="input.jenisMintaDarahKet" type="text" style="width: auto !important;"
                            v-if="input.jenismintaDarah && input.jenismintaDarah == 'Cadangan'" />

                            <VRadio v-model="input.jenismintaDarah" value="Siap Pakai" label="Siap Pakai" name="jenismintaDarah" color="primary"
                                square />
                            <VInput v-model="input.jenisMintaDarahKet" type="text" style="width: auto !important;"
                            v-if="input.jenismintaDarah && input.jenismintaDarah == 'Siap Pakai'" />

                            <VRadio v-model="input.jenismintaDarah" value="Cito" label="Cito/Emergency" name="jenismintaDarah" color="primary"
                                square />
                            <VInput v-model="input.jenisMintaDarahKet" type="text" style="width: auto !important;"
                            v-if="input.jenismintaDarah && input.jenismintaDarah == 'Cito'" />

                        </VControl>
                    </VField>
                </div>

                <div class="column is-12">
                    <VField label="Golongan Darah">
                        <VControl>
                            <VRadio v-model="input.jenisGolonganDarah" value="A" label="A"
                             color="primary" square />
                            <VRadio v-model="input.jenisGolonganDarah" value="B" label="B"
                             color="primary" square />
                            <VRadio v-model="input.jenisGolonganDarah" value="O" label="O"
                             color="primary" square />
                            <VRadio v-model="input.jenisGolonganDarah" value="AB" label="AB"
                             color="primary" square />
                            <VRadio v-model="input.jenisGolonganDarah" value="Lainnya" label="Lainnya" 
                             color="primary" square />
                            <VInput v-model="input.jenisGolonganDarah" type="text" style="width: auto !important;"
                            v-if="input.jenisGolonganDarah && input.jenisGolonganDarah == 'Lainnya'" />

                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField label="RHESUS + / -">
                        <VControl>
                            <VRadio v-model="input.resus" value="POSITIF" label="POSITF"
                             color="primary" square />
                            <VRadio v-model="input.resus" value="NEGATTIF" label="NEGATIF"
                             color="danger" square />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <hr>
                    
                    <table class="table is-striped is-fullwidth">
                        <thead>
                            <tr>
                                <th class="" style="text-align: left;">Darah</th>
                                <!-- <th class="" style="text-align: left;">RHESUS + / -</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Darah Lengkap (Segar, Simpan)"
                                                label="Darah Lengkap (Segar, Simpan)"
                                                v-model="input.darahLengkap" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input.darahLengkapQty" type="number"/>
                                            <p class="help">
                                                / Kantong
                                            </p>
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Sel Darah Merah Pekat"
                                                label="Sel Darah Merah Pekat"
                                                v-model="input.darahMerahPekat" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input.darahMerahPekatQty" type="number"/>
                                            <p class="help">
                                                / Kantong
                                            </p>
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Sel Darah Merah Dicuci"
                                                label="Sel Darah Merah Dicuci"
                                                v-model="input.darahMerahDicuci" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input.darahMerahDicuciQty" type="number"/>
                                            <p class="help">
                                                / Kantong
                                            </p>
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Buffycoat"
                                                label="Buffycoat"
                                                v-model="input.buffyCoat" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input.buffyCoatQty" type="number"/>
                                            <p class="help">
                                                / Kantong
                                            </p>
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Trombosit Konsentrat"
                                                label="Trombosit Konsentrat"
                                                v-model="input.trombositKonsetrat" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input.trombositKonsetratQty" type="number"/>
                                            <p class="help">
                                                / Kantong
                                            </p>
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Plasma kaya Trombosit"
                                                label="Plasma kaya Trombosit"
                                                v-model="input.plasmaTrombosit" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input.plasmaTrombositQty" type="number"/>
                                            <p class="help">
                                                / Kantong
                                            </p>
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Plasma Cair"
                                                label="Plasma Cair"
                                                v-model="input.plasmaCair" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input.plasmaCairQty" type="number"/>
                                            <p class="help">
                                                / Kantong
                                            </p>
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Plasma Segar"
                                                label="Plasma Segar"
                                                v-model="input.plasmaSegar" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input.plasmaSegarQty" type="number"/>
                                            <p class="help">
                                                / Kantong
                                            </p>
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Plasma Segar Beku"
                                                label="Plasma Segar Beku"
                                                v-model="input.plasmaSegarBeku" />
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput v-model="input.plasmaSegarBekuQty" type="number"/>
                                            <p class="help">
                                                / Kantong
                                            </p>
                                        </VControl>
                                    </VField>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="column is-6 mt-3 text-center">
                    <span class="kayalabel">Petugas yang Mengambil Contoh Darah</span>
                    <VField class="text-center">
                        <img v-if="props.isDashLab == true"
                        :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (input.pegawaipengambildarah ? input.pegawaipengambildarah.label : '-')">
                        <!-- <TandaTangan :elemenID="'ttd_pasien'" :width="'150'" :height="'150'" class="dek" /> -->
                    </VField>
                    <VField>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.pegawaipengambildarah" :suggestions="d_Pegawai"
                                :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Cari Pegawai...." />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6 mt-3 text-center">
                    <span class="kayalabel">Dokter yang Meminta</span>
                    <VField>
                        <img v-if="props.isDashLab == true"
                        :src="'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' + (input.dokterpeminta ? input.dokterpeminta.label : '-')">
                        <!-- <TandaTangan :elemenID="'ttd_dpjpLama'" :width="'150'" :height="'150'" class="dek" /> -->
                    </VField>
                    <VField>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.dokterpeminta"
                                :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Cari Dokter...." />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12 text-center">
                    <hr>
                    <h1 style="font-weight: bold; font-size: 18px;">
                        DIISI OLEH PETUGAS BANK DARAH RSUD BALI MANDARA
                    </h1>
                    <hr>
                </div>
                <div class="column is-6">
                    <VField label="No. RM">
                        <VControl>
                            <VInput type="text" v-model="input.nocm" :value="props.pasien.nocm" disabled="true" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField label="Nama Pasien">
                        <VControl>
                            <VInput type="text" v-model="input.namapasien" :value="props.pasien.namapasien" disabled="true" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <VField label="Tanggal Lahir">
                                <VControl>
                                    <VInput type="text" v-model="input.tgllahir" :value="props.pasien.tgllahir"
                                        disabled="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Jenis Kelamin">
                                <VControl>
                                    <VInput type="text" v-model="input.jeniskelamin" :value="props.pasien.jeniskelamin"
                                        disabled="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField label="Umur Saat Datang">
                                <VControl>
                                    <VInput type="text" v-model="input.umur" :value="props.pasien.umur"
                                        disabled="true" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <VField label="ID BDRS">
                        <VControl>
                            <VInput type="text" v-model="input.idbdrs"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12 text-center mb-5">
                    <h1 style="font-weight: bold; font-size: 18px;" class="mb-5 mt-5">
                        Golongan Darah
                    </h1>

                    <table class="table is-striped is-fullwidth mt-5">
                        <thead>
                            <tr>
                                <th style="text-align: center;">ABO</th>
                                <th style="text-align: center;">RHESUS + / -</th>
                                <th style="text-align: center;">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(value, index) in input.golonganDarahPetugas">
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.abo" type="text"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.rhesus" type="number"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td class="text-center">
                                    <VButtons>
                                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewGolongan()" color="info"
                                            v-tooltip.bubble="'Tambah '">
                                        </VIconButton>
                                        <VIconButton v-if="index > 0" class="mt-1" type="button" raised circle icon="feather:trash"
                                            @click="removeGolongan(index)" color="danger">
                                        </VIconButton>
                                    </VButtons>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="column is-12">
                    <VField label="Catatan Golongan">
                        <VControl>
                            <VTextarea v-model="input.catatanGolongan"></VTextarea>
                        </VControl>
                    </VField>
                </div>

                <div class="column is-12 text-center mb-5" style="overflow: scroll;">

                    <table class="table" style="width: 100% !important;">
                        <thead>
                            <tr class="tr-pri">
                                <th style="vertical-align: inherit;" class="th-pri"></th>
                                <th style="vertical-align: inherit;" class="th-pri"></th>
                                <th style="vertical-align: inherit;" class="th-pri"></th>
                                <th style="vertical-align: inherit;" class="th-pri" colspan="2">Golongan Darah</th>
                                <th style="vertical-align: inherit;" class="th-pri" colspan="3">Hasil Uji Cocok Serasi</th>
                                <th style="vertical-align: inherit;" class="th-pri" colspan="3">Analis Pemeriksa</th>
                                <th style="vertical-align: inherit;" class="th-pri" colspan="2">Keluar</th>
                                <th style="vertical-align: inherit;" class="th-pri">Petugas BDRS</th>
                                <th style="vertical-align: inherit;" class="th-pri">Petugas yg Mengambil</th>
                            </tr>
                            <tr class="tr-pri">
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">No Reg</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">No Kantong</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">Jenis Darah</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">ABO</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">Rhesus</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">Major</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">Minor</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">Auto Control</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">Nama</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">Tanggal</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">Jam</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">Tanggal</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">Jam</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">Nama</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">Nama</th>
                                <th class="tr-pri" style="text-align: center;vertical-align: inherit;">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(value, index) in input.detail">
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.noreg" type="number"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.nokantong" type="number"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.jenisdarah" type="text"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.abo" type="text"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.rhesus" type="text"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.major" type="text"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.minor" type="text"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.autocontrol" type="text"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.nama" type="nama"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField class="is-rounded-select is-autocomplete-select">
                                        <VDatePicker v-model="value.tanggal" mode="datetime" style="width: 100%;" trim-weeks is24hr>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.70rem;">
                                                    <VControl class="prime-auto" fullwidth>
                                                        <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VDatePicker v-model="value.jam" mode="time" style="width: 100%;" trim-weeks is24hr>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.70rem;">
                                                    <VControl class="prime-auto" fullwidth>
                                                        <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField class="is-rounded-select is-autocomplete-select">
                                        <VDatePicker v-model="value.tanggalkeluar" mode="datetime" style="width: 100%;" trim-weeks is24hr>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.70rem;">
                                                    <VControl class="prime-auto" fullwidth>
                                                        <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VDatePicker v-model="value.jamkeluar" mode="time" style="width: 100%;" trim-weeks is24hr>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField style="margin-bottom: 0.70rem;">
                                                    <VControl class="prime-auto" fullwidth>
                                                        <VInput :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.namaPetugasBDRS" type="text"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td class="td-pri" style="vertical-align: inherit;">
                                    <VField>
                                        <VControl>
                                            <VInput v-model="value.namaPetugasAmbil" type="text"/>
                                        </VControl>
                                    </VField>
                                </td>
                                <td  class="text-center td-pri" style="vertical-align: inherit;">
                                    <VButtons> 
                                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                                            v-tooltip.bubble="'Tambah '">
                                        </VIconButton>
                                        <VIconButton v-if="index > 0" class="mt-1" type="button" raised circle icon="feather:trash"
                                            @click="removeItem(index)" color="danger">
                                        </VIconButton>
                                    </VButtons>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                    :loading="isLoading" @click="simpan()"> Simpan
                </VButton>
            </VCard>

        <!-- </template>
    </MasterEMR> -->
</template>

<script setup lang="ts">
import MasterEMR from './master-emr.vue'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'

useHead({
    title:'form order darah -'+import.meta.env.VITE_PROJECT
})

// let ID_PASIEN = useRoute().query.nocmfk as string
// let NOREC_PD = useRoute().query.norec_pd as string 
let norec_emr = useRoute().query.norec_emr as string
const masterRef = ref(null)
const dataPasien = '';
const d_Dokter: any = ref([]);
const dataTTD: any = ref([])
const NOREC_EMRPASIEN: any = ref('')
const isLoading: any = ref(false);
const d_Pegawai: any = ref([])
const d_JenisDiagnosa: any = ref([])
const COLLECTION: any = ref('FormulirPermintaanDarah') //table mongodb

const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
// const props = withDefaults(
//     defineProps<{
//         pasien?: any,
//         registrasi?: any,
//         FORM_NAME?: string,
//         FORM_URL?: string,
//         COLLECTION?: string,
//         NOREC_PD:{
//             type:object as Prototype<any>
//         },
//     }>(),
//     {
//         pasien: {},
//         registrasi: {},
//         FORM_NAME: '',
//         FORM_URL: '',
//         COLLECTION: '',
//     }
// )
const props = defineProps({
    norec_pd:{
        type:Object as ProtoType<any>
    },
    registrasi:{
        type:Object as ProtoType<any>
    },
    pasien:{
        type:Object as ProtoType<any>
    },
    ID_PASIEN:{
        type:Object as ProtoType<any>
    },
    noorder:{
        type:Object as ProtoType<any>
    },
    ruangantujuan:{
        type:Object as ProtoType<any>
    },
    isDashLab:{
        type:Boolean,
        default:false
    }
})
const rhesus_m = ref<any[]>([
  { value: 1, label: "POSITIF" },
  { value: 2, label: "NEGATIF" }
]);
let NOREC_PD = props.norec_pd
let ID_PASIEN= props.ID_PASIEN
const input: any = ref({
    detail: [
        {
            no: 1,
            jam: new Date(),
            jamkeluar: new Date(),
            tanggal: new Date(),
            tanggalkeluar: new Date()
        }
    ],
    golonganDarahPetugas: [
        {
            no: 1,
            abo: '',
            rhesus: 0
        }
    ],
    tanggal: new Date(),
    tglPermintaan: new Date(),
    nocm: props.pasien.nocm,
    namapasien: props.pasien.namapasien,
    tgllahir: props.pasien.tgllahir,
    jeniskelamin: props.pasien.jeniskelamin,
    umur: props.pasien.umur,
    alamat: props.pasien.alamatlengkap,
    noorder:props.noorder
})

const fetchDokter = async (filter: any) => {
    d_Dokter.value = await H.fetchDokter(filter);
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    console.log("Pasien", props.pasien);
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
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  return new Promise((resolve, rejects) => {
    console.log("PROPS DATA", props)
    useApi().get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&noorder=${props.noorder}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        if (response.length) {
          input.value = response[0] //set ke inputan
          if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
          }
          if(props.isTTD) {
            if(response[0][props.fieldTTD] && Array.isArray(response[0][props.fieldTTD]) ) {
              // dataTTD.value = response[0][props.fieldTTD];
              response[0][props.fieldTTD].forEach(element => {
                H.tandaTangan().set("ttd_"+element.no, element.ttd);
              });
            }else {
              dataTTD.value = response[0][props.fieldTTD];
              H.tandaTangan().set("dataTTD", dataTTD.value);
            }
          }
  
          return resolve(response[0])
        }else {
          return resolve(null)
        }
      })
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
            input.value.namatemplate = null
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const addNewItem = () => {
    input.value.detail.push({
        no: input.value.detail[input.value.detail.length - 1].no + 1,
        jam: new Date(),
        jamkeluar: new Date(),
        tanggal: new Date(),
        tanggalkeluar: new Date()
    });
}
const removeItem = (index: any) => {
    input.value.detail.splice(index, 1)
}

const addNewGolongan = () => {
    input.value.golonganDarahPetugas.push({
        no: input.value.golonganDarahPetugas[input.value.golonganDarahPetugas.length - 1].no + 1,
        abo: '',
        rhesus: 0
    });
}
const removeGolongan = (index: any) => {
    input.value.golonganDarahPetugas.splice(index, 1)
}

const addNewPeralihan = () => {
    input.value.peralihanDPJP.push({
        no: input.value.peralihanDPJP[input.value.peralihanDPJP.length - 1].no + 1,
    });
}
const removePeralihan = (index: any) => {
    input.value.peralihanDPJP.splice(index, 1)
}

const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

const triggerAllData = async () => {
    if (masterRef.value) {
        let ss = await masterRef.value.loadRiwayat()
        if (ss != null) {
            input.value = ss
        }
    }
}

async function fetchDiagnosa(filter: any) {
    let q = '';
    console.log("DATA FILTER", filter)
    if (filter != undefined) {
        q = filter.query;
    }
    const response = await useApi().get(`/diagnosa/diagnosa-x-paging?name=${q}&limit=10`)
    d_JenisDiagnosa.value = response.diagnosa.map((item: any) => {
        return { value: item.id, label: item.kddiagnosa + " - " + item.namadiagnosa, namadiagnosa: item.namadiagnosa }
    })
}

onMounted(() => {
    setView()
    triggerAllData()
    fetchDokter();
    fetchDiagnosa()
    loadRiwayat()
})


</script>

<style lang="scss">
.text-bold {
    font-weight: bold;
}
.kayalabel {
    color: hsl(0deg, 0%, 4%) !important;
}
.kayalabel {
    font-family: var(--font);
    font-size: 0.9rem;
    // color: var(--light-text) !important;
    font-weight: 400;
    text-overflow: ellipsis;
    overflow: hidden;
    width: 160px !important;
    height: 1.2rem;
    white-space: nowrap;
}
</style>