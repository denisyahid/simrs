<template>
    <MasterEMR :isTTD="true" :fieldTTD="'peralihanDPJP'" @simpan="simpan()" @simpanTemplate="simpanTemplate()"
        :ID_PASIEN="ID_PASIEN" :NOREC_PD="NOREC_PD" :norec_emr="norec_emr" :input="input" :FORM_NAME="lockedFormName"
        :FORM_URL="props.FORM_URL" :registrasi="props.registrasi" :pasien="props.pasien" :COLLECTION="props.COLLECTION"
        ref="masterRef" :isLoading="isLoading">
        <template #content>
            <div class="column">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Pre Anastesi" class="is-warning">
                            <div class="column is-12">
                                <VCard>
                                    <h1 style="font-weight: bold; margin-bottom: 1.5rem;">
                                        Catatan Anestesi Ibsa
                                    </h1>
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <span class="label-apb">Tanggal Tindakan</span>
                                            <VField class="pt-2">
                                                <VControl raw subcontrol>
                                                    <VInput type="date" v-model="input.dateTindakan"></VInput>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <span class="label-apb">Jam Tiba diruang</span>
                                            <VField class="pt-2">
                                                <VControl raw subcontrol>
                                                    <VInput type="time" v-model="input.time"></VInput>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <div>
                                                <h1>Spesialis Anestesi</h1>
                                                <VControl class="prime-auto">
                                                    <AutoComplete v-model="input.spesialis" :suggestions="d_Petugas"
                                                        @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="column is-4">
                                            <div>
                                                <h1>Dokter Operator</h1>
                                                <VControl class="prime-auto">
                                                    <AutoComplete v-model="input.dokterOperator" :suggestions="d_Dokter"
                                                        @complete="fetchDokter($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                                </VControl>
                                            </div>
                                        </div>
                                        <div class="column is-4">
                                            <div>
                                                <h1>Perawat Anestesi</h1>
                                                <VControl class="prime-auto">
                                                    <AutoComplete v-model="input.perawat" :suggestions="d_Petugas"
                                                        @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <span class="label-apb">Ruang persiapan</span>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="OK IGD" label="OK IGD" class="p-0"
                                                                color="primary" square
                                                                v-model="input.ruanganPersiapan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="OK IBSA" label="OK IBSA" class="p-0"
                                                                color="primary" square
                                                                v-model="input.ruanganPersiapan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Lainnya" label="Lainnya" class="p-0"
                                                                color="primary" square
                                                                v-model="input.ruanganPersiapan" />

                                                            <VInput type="text" class="input" placeholder="Ruangan"
                                                                v-model="input.ruanganPersiapan"
                                                                v-if="input.ruanganPersiapan == 'Lainnya'" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pl-3">
                                        <div class="column is-12">
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-6">
                                                    <VField label="Diagnosa Prabedah">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.diagnosaPrabedah" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6">
                                                    <VField label="Jenis Pembedahan">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.jenisPembedahan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <span class="label-apb">Keadaan pra anestesi</span>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Awake" label="Awake" class="p-0"
                                                                color="primary" square
                                                                v-model="input.keadaanPraAnestesi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Anxious" label="Anxious" class="p-0"
                                                                color="primary" square
                                                                v-model="input.keadaanPraAnestesi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Uncooperative" label="Uncooperative"
                                                                class="p-0" color="primary" square
                                                                v-model="input.keadaanPraAnestesi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Calm" label="Calm" class="p-0"
                                                                color="primary" square
                                                                v-model="input.keadaanPraAnestesi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Lethargic" label="Lethargic"
                                                                class="p-0" color="primary" square
                                                                v-model="input.keadaanPraAnestesi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Unresponsive" label="Unresponsive"
                                                                class="p-0" color="primary" square
                                                                v-model="input.keadaanPraAnestesi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <span class="label-apb">Alergi</span>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Ya" label="Ya" class="p-0"
                                                                color="primary" square v-model="input.alergi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Tidak" label="Tidak" class="p-0"
                                                                color="primary" square v-model="input.alergi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pl-3">
                                        <div class="column is-12">
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField label="VAS">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder="VAS"
                                                                v-model="input.vas" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField label="TB">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder="Tinggi Badan"
                                                                v-model="input.tinggiBadan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField label="BB">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder="Berat Badan"
                                                                v-model="input.beratBadan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pl-3">
                                        <div class="column" style="overflow: auto;">
                                            <table class="tg">
                                                <thead>
                                                    <tr>
                                                        <th width="10%" class="col-stuck">Waktu</th>
                                                        <th v-for="index in jumlahIndexVS">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['waktu_' + index]"
                                                                        type="time" placeholder="Pick an hour" />
                                                                </VControl>
                                                            </VField>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>Suhu</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexVS">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['suhu_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>TVS</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexVS">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['tvs_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>R</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexVS">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['r_' + index]" type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>N</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexVS">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['n_' + index]" type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>TD</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexVS">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['td_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <!-- <VIconButton color="info" light raised circle
                                                            icon="feather:plus-circle" /> -->
                                                            <VButton type="button" color="info" raised rounded
                                                                icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                                @click="addVS()">
                                                                Tambah
                                                            </VButton>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pt-3">
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;" class="text-center">Grafik Tanda Vital</h1>
                                            <VCard style="border-radius: 16px;">
                                                <highcharts :options="chartOptions1"></highcharts>
                                            </VCard>
                                        </div>
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;" class="text-center">Grafik Tekanan Darah</h1>
                                            <VCard style="border-radius: 16px;">
                                                <highcharts :options="chartOptionsTD1"></highcharts>
                                            </VCard>
                                        </div>
                                    </div>
                                </VCard>
                            </div>
                            <div class="column is-12">
                                <VCard>
                                    <h1 style="font-weight: bold; margin-bottom: 1.5rem;">
                                        Status Fisik ASA
                                    </h1>
                                    <div class="columns is-multiline pl-3">
                                        <div class="column is-12">
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-3">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="1" label="1" class="p-0"
                                                                color="primary" square v-model="input.CBAsa1" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="2" label="2" class="p-0"
                                                                color="primary" square v-model="input.CBAsa2" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="3" label="3" class="p-0"
                                                                color="primary" square v-model="input.CBAsa3" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="4" label="4" class="p-0"
                                                                color="primary" square v-model="input.CBAsa4" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <VField label="Penyulit pra anestesi">
                                                <VControl>
                                                    <VTextarea v-model="input.penyulitAnestesi" rows="3"
                                                        placeholder="" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField label="Catatan Penting">
                                                <VControl>
                                                    <VTextarea v-model="input.catatanPenting" rows="3" placeholder="" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </VCard>
                            </div>
                            <div class="column is-12">
                                <VCard>
                                    <h1 style="font-weight: bold; margin-bottom: 1.5rem;">
                                        Obat Premedikasi Yang Diberikan:
                                    </h1>
                                    <div class="columns is-multiline pl-3">
                                        <div class="column is-12">
                                            <span class="label-apb">Oral</span>
                                            <div class="columns is-multiline pt-3"
                                                v-for="(itemPremedikasi, indexPremedikasi) in input.details.premedikasi">
                                                <div class="column is-3">
                                                    <VField label="Oral" horizontal>
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="itemPremedikasi.oral" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField label="Dosis" horizontal>
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="itemPremedikasi.dosis" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField label="Jam" horizontal>
                                                        <VControl>
                                                            <VInput type="time" class="input" placeholder=""
                                                                v-model="itemPremedikasi.jam" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VButton type="button" color="info" raised rounded
                                                        icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                        v-if="indexPremedikasi == 0"
                                                        @click="addPremedikasiOral('oral')">
                                                        Tambah
                                                    </VButton>
                                                    <VButton type="button" color="danger" raised rounded
                                                        icon="feather:trash" class=" mr-3 mt-0 mb-0"
                                                        v-if="indexPremedikasi > 0"
                                                        @click="removePremedikasiOral(indexPremedikasi, 'oral')">
                                                        Hapus
                                                    </VButton>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <span class="label-apb">IM</span>
                                            <div class="columns is-multiline pt-3"
                                                v-for="(itemPremedikasi, indexPremedikasi) in input.details.premedikasiIM">
                                                <div class="column is-3">
                                                    <VField label="IM" horizontal>
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="itemPremedikasi.im" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField label="Dosis" horizontal>
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="itemPremedikasi.dosis" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField label="Jam" horizontal>
                                                        <VControl>
                                                            <VInput type="time" class="input" placeholder=""
                                                                v-model="itemPremedikasi.jam" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VButton type="button" color="info" raised rounded
                                                        icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                        v-if="indexPremedikasi == 0" @click="addPremedikasiOral('IM')">
                                                        Tambah
                                                    </VButton>
                                                    <VButton type="button" color="danger" raised rounded
                                                        icon="feather:trash" class=" mr-3 mt-0 mb-0"
                                                        v-if="indexPremedikasi > 0"
                                                        @click="removePremedikasiOral(indexPremedikasi, 'IM')">
                                                        Hapus
                                                    </VButton>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <span class="label-apb">IV</span>
                                            <div class="columns is-multiline pt-3"
                                                v-for="(itemPremedikasi, indexPremedikasi) in input.details.premedikasiIV">
                                                <div class="column is-3">
                                                    <VField :label="indexPremedikasi + 1" horizontal>
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="itemPremedikasi.iv" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField label="Dosis" horizontal>
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="itemPremedikasi.dosis" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VField label="Jam" horizontal>
                                                        <VControl>
                                                            <VInput type="time" class="input" placeholder=""
                                                                v-model="itemPremedikasi.jam" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-3">
                                                    <VButton type="button" color="info" raised rounded
                                                        icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                        v-if="indexPremedikasi == 0" @click="addPremedikasiOral('IV')">
                                                        Tambah
                                                    </VButton>
                                                    <VButton type="button" color="danger" raised rounded
                                                        icon="feather:trash" class=" mr-3 mt-0 mb-0"
                                                        v-if="indexPremedikasi > 0"
                                                        @click="removePremedikasiOral(indexPremedikasi, 'IV')">
                                                        Hapus
                                                    </VButton>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </VCard>
                            </div>
                            <div class="column is-12">
                                <VCard>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold; margin-bottom: 1.5rem;">
                                                REKOMENDASI TINDAKAN ANESTESI YANG DIPILIH
                                            </h1>
                                            <div class="columns is-multiline pt-3">
                                                <Panel class="column is-12" header="Anestesi umum">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-4">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox true-value="Anestesi umum"
                                                                        label="Anestesi umum :" class="p-0"
                                                                        color="primary" square
                                                                        v-model="input.tindakanAnestesi" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-8">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox true-value="Intravena"
                                                                                label="Intravena" class="p-0"
                                                                                color="primary" square
                                                                                v-model="input.tindakanIntravena" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox true-value="Sungkup muka"
                                                                                label="Sungkup muka" class="p-0"
                                                                                color="primary" square
                                                                                v-model="input.tindakanSungkupMuka" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                true-value="Laringeal mask airway"
                                                                                label="Laringeal mask airway"
                                                                                class="p-0" color="primary" square
                                                                                v-model="input.tindakanLarigeal" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                true-value="Nasotracheal tube(NTT)"
                                                                                label="Nasotracheal tube(NTT)"
                                                                                class="p-0" color="primary" square
                                                                                v-model="input.tindakanPipa" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                true-value="Oraltracheal tube(OTT)"
                                                                                label="Oraltracheal tube(OTT)"
                                                                                class="p-0" color="primary" square
                                                                                v-model="input.tindakanOraltracheal" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </Panel>

                                                <Panel class="column is-12" header="Regional anestesi">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-4">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox true-value="Regional anestesi"
                                                                        label="Regional anestesi :" class="p-0"
                                                                        color="primary" square
                                                                        v-model="input.tindakanRegionalAnestesi" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-8">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox true-value="Spinal anestesi blok"
                                                                                label="Spinal anestesi blok" class="p-0"
                                                                                color="primary" square
                                                                                v-model="input.tindakanSpinal" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox true-value="Epidural"
                                                                                label="Epidural" class="p-0"
                                                                                color="primary" square
                                                                                v-model="input.tindakanEpidural" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                true-value="Kombinasi spiral epidural"
                                                                                label="Kombinasi spiral epidural"
                                                                                class="p-0" color="primary" square
                                                                                v-model="input.tindakanKombinasiSpiral" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                true-value="Peripheral nerve block"
                                                                                label="Peripheral nerve block"
                                                                                class="p-0" color="primary" square
                                                                                v-model="input.tindakanPeripheral" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </Panel>

                                                <Panel class="column is-12" header="Anestesi umum + regional anestesi">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-4">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox
                                                                        true-value="Anestesi umum + regional anestesi"
                                                                        label="Anestesi umum + regional anestesi"
                                                                        class="p-0" color="primary" square
                                                                        v-model="input.tindakanUmumRegional" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </Panel>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold; margin-bottom: 1.5rem;">
                                                Teknik Khusus
                                            </h1>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Hipotensi" label="Hipotensi"
                                                                class="p-0" color="primary" square
                                                                v-model="input.teknikHipotensi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Ventilasi satu paru"
                                                                label="Ventilasi satu paru" class="p-0" color="primary"
                                                                square v-model="input.teknikVentilasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="CPB" label="CPB" class="p-0"
                                                                color="primary" square v-model="input.teknikCPB" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Sirkulatory Arrest"
                                                                label="Sirkulatory Arrest" class="p-0" color="primary"
                                                                square v-model="input.teknikArrest" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-8">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Lain-lain" label="Lain-lain"
                                                                class="p-0" color="primary" square
                                                                v-model="input.teknikLainnya" />

                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.teknikLainnya"
                                                                v-if="input.teknikLainnya == 'Lain-lain'" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </VCard>
                            </div>
                            <div class="column is-12">
                                <VCard>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <!-- <span class="label-apb">Infus perifer : Tempat ukuran</span> -->
                                            <h1 style="font-weight: bold;">Infus perifer : Tempat ukuran</h1>
                                            <div class="columns is-multiline pt-3"
                                                v-for="(itemInfus, indexInfus) in input.details.infusperifer">
                                                <div class="column is-5">
                                                    <VField>
                                                        <VControl>
                                                            <VInput type="text" v-model="itemInfus.tempat" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-5">
                                                    <VField addons>
                                                        <VControl expanded>
                                                            <VInput type="text" v-model="itemInfus.ukuran" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>g</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-2">
                                                    <VButton type="button" color="info" raised rounded
                                                        icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                        v-if="indexInfus == 0" @click="addnewInfusPerifer()">
                                                        Tambah
                                                    </VButton>
                                                    <VButton type="button" color="danger" raised rounded
                                                        icon="feather:trash" class=" mr-3 mt-0 mb-0"
                                                        v-if="indexInfus > 0"
                                                        @click="input.details.infusperifer.splice(indexInfus, 1)">
                                                        Hapus
                                                    </VButton>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-6">
                                            <span class="label-apb">Posisi Kiri</span>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Terlentang (Supine)"
                                                                label="Terlentang (Supine)" class="p-0" color="primary"
                                                                square v-model="input.CBPosisiKiri" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Lithonomi" label="Lithonomi"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBPosisiKiri" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Prone" label="Prone" class="p-0"
                                                                color="primary" square v-model="input.CBPosisiKiri" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Lateral" label="Lateral" class="p-0"
                                                                color="primary" square v-model="input.CBPosisiKiri" />
                                                        </VControl>
                                                    </VField>
                                                </div>

                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Lain-lain" label="Lain-lain"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBPosisiKiri" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Perlindungan mata"
                                                                label="Perlindungan mata" class="p-0" color="primary"
                                                                square v-model="input.CBPosisiKiri" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-6">
                                            <span class="label-apb">Posisi Kanan</span>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Terlentang (Supine)"
                                                                label="Terlentang (Supine)" class="p-0" color="primary"
                                                                square v-model="input.CBPosisiKanan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Lithonomi" label="Lithonomi"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBPosisiKanan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Prone" label="Prone" class="p-0"
                                                                color="primary" square v-model="input.CBPosisiKanan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Lateral" label="Lateral" class="p-0"
                                                                color="primary" square v-model="input.CBPosisiKanan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Lain-lain" label="Lain-lain"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBPosisiKanan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Perlindungan mata"
                                                                label="Perlindungan mata" class="p-0" color="primary"
                                                                square v-model="input.CBPosisiKanan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;">Penilaian Pra Induksi (PKL)</h1>
                                            <!-- <span>
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" v-model="input.nilaiPraInduksi" placeholder="PKL"/>
                                                    </VControl>
                                                </VField>
                                            </span> -->
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-2">
                                                    <VField label="Pukul" vertical>
                                                        <VInput type="time" v-model="input.nilaiPraInduksiJam" />
                                                    </VField>
                                                </div>
                                                <div class="column is-2">
                                                    <label>
                                                        Suhu
                                                    </label>
                                                    <VField addons vertical>
                                                        <VControl expanded>
                                                            <VInput type="text" v-model="input.nilaiPraInduksiSuhu" />
                                                        </VControl>
                                                        <VControl>
                                                            <VButton static>°C</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-2">
                                                    <label>
                                                        Nadi
                                                    </label>
                                                    <VField addons vertical>
                                                        <VControl expanded>
                                                            <VInput type="text" v-model="input.nilaiPraInduksiNadi" />
                                                        </VControl>
                                                        <VControl>
                                                            <VButton static>x/menit</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-2">
                                                    <label>
                                                        Tensi
                                                    </label>
                                                    <VField addons vertical>
                                                        <VControl expanded>
                                                            <VInput type="text" v-model="input.nilaiPraInduksiTensi" />
                                                        </VControl>
                                                        <VControl>
                                                            <VButton static>mmHG</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-2">
                                                    <label>
                                                        Respirasi
                                                    </label>
                                                    <VField addons vertical>
                                                        <VControl expanded>
                                                            <VInput type="text"
                                                                v-model="input.nilaiPraInduksiRespirasi" />
                                                        </VControl>
                                                        <VControl>
                                                            <VButton static>x/menit</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-2">
                                                    <label>
                                                        Saturasi
                                                    </label>
                                                    <VField addons vertical>
                                                        <VControl expanded>
                                                            <VInput type="text"
                                                                v-model="input.nilaiPraInduksiSaturasi" />
                                                        </VControl>
                                                        <VControl>
                                                            <VButton static>%</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="EKG">
                                                        <VControl>
                                                            <VInput type="text" v-model="input.nilaiPraInduksiEKG" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </VCard>
                            </div>
                        </Fieldset>
                    </div>

                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Intra Anastesi" class="is-warning">
                            <div class="column is-12">
                                <VCard>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;">Induksi</h1>
                                            <!-- <span class="label-apb">
                                                Induksi
                                            </span> -->
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-10">
                                                            <VField label="Intravena">
                                                                <VControl>
                                                                    <VInput type="text"
                                                                        v-model="input.nilaiInduksiIntravena" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-2">
                                                            <VField label="Jam">
                                                                <VControl>
                                                                    <VInput type="time" class="input" placeholder=""
                                                                        v-model="input.nilaiInduksiIntravenaJam"
                                                                        style="width: 100px !important" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <div class="columns is-multiline"
                                                        v-for="(induksiObat, indexInduksi) in input.details.induksiObat">
                                                        <div class="column is-3">
                                                            <VField label="Obat">
                                                                <VControl>
                                                                    <VInput type="text" v-model="induksiObat.obat" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-3">
                                                            <VField label="Dosis">
                                                                <VControl>
                                                                    <VInput type="text" v-model="induksiObat.dosis" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-3">
                                                            <VField label="Jumlah">
                                                                <VControl>
                                                                    <VInput type="text" v-model="induksiObat.jumlah" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-3 my-auto pt-5">
                                                            <VButton type="button" color="info" raised rounded
                                                                icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                                v-if="indexInduksi == 0" @click="addnewInduksiObat()">
                                                                Tambah
                                                            </VButton>
                                                            <VButton type="button" color="danger" raised rounded
                                                                icon="feather:trash" class=" mr-3 mt-0 mb-0"
                                                                v-if="indexInduksi > 0"
                                                                @click="input.details.induksiObat.splice(indexInduksi, 1)">
                                                                Hapus
                                                            </VButton>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Inhalasi">
                                                        <VControl>
                                                            <VInput type="text" v-model="input.nilaiInduksiInhalasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6">
                                                    <VField label="Nama Gas">
                                                        <VControl>
                                                            <VInput type="text" v-model="input.nilaiInduksiInhalasiGas" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6">
                                                    <VField label="Tekanan Inhalasi">
                                                        <VControl>
                                                            <VInput type="text"
                                                                v-model="input.nilaiInduksiInhalasiTekanan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <!-- <span class="label-apb">
                                                Jalan Nafas (tulis ukuran)
                                            </span> -->
                                            <h1 style="font-weight: bold;">Jalan Nafas (tulis ukuran)</h1>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-12">
                                                    <VField label="Hanya facemask">
                                                        <VControl>
                                                            <VInput type="text" v-model="input.jalanNafasFaceMask" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6">
                                                    <VField label="ETT">
                                                        <VControl>
                                                            <VInput type="text" v-model="input.jalanNafasETT" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6">
                                                    <VField label="Level dibibir">
                                                        <VControl>
                                                            <VInput type="text" v-model="input.jalanNafasDibibir" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Oral">
                                                        <VControl>
                                                            <VInput type="text" v-model="input.jalanNafasOral" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Nasal">
                                                        <VControl>
                                                            <VInput type="text" v-model="input.jalanNafasNasal" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="LM">
                                                        <VControl>
                                                            <VInput type="text" v-model="input.jalanNafasLM" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Trakheosnomi">
                                                        <VControl>
                                                            <VInput type="text" v-model="input.jalanNafasTrakheosnomi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Lain Lain">
                                                        <VControl>
                                                            <VInput type="text" v-model="input.jalanNafasLainnya" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <!-- <span class="label-apb">Intubasi</span> -->
                                            <h1 style="font-weight: bold;">Intubasi</h1>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Sesudah tidur" label="Sesudah tidur"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Blind" label="Blind" class="p-0"
                                                                color="primary" square v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Oral" label="Oral" class="p-0"
                                                                color="primary" square v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Nasal" label="Nasal" class="p-0"
                                                                color="primary" square v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Ka" label="Ka" class="p-0"
                                                                color="primary" square v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Ki" label="Ki" class="p-0"
                                                                color="primary" square v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Fiber optik" label="Fiber optik"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Trakheostomi" label="Trakheostomi"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Preoksigenisasi" label="Preoksigenisasi"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Glidescope" label="Glidescope"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Mudah mask ventilasi"
                                                                label="Mudah mask ventilasi" class="p-0" color="primary"
                                                                square v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Mudah intubasi" label="Mudah intubasi"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Sulit intubasi" label="Sulit intubasi"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Dengan stilet" label="Dengan stilet"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Tekanan luar larings"
                                                                label="Tekanan luar larings" class="p-0" color="primary"
                                                                square v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Pack" label="Pack" class="p-0"
                                                                color="primary" square v-model="input.CBIntubasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Laringoscope View">
                                                        <VControl raw subcontrol>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.laringoscopeView" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField label="Comark">
                                                        <VControl icon="fas fa-sticky-note" fullwidth
                                                            class="prime-auto-select">
                                                            <Dropdown v-model="input.comarkInput" :options="d_comark"
                                                                :optionLabel="'label'" style="width: 100%;" :filter="true"
                                                                showClear />
                                                            <!-- <VInput type="text" class="input" placeholder="I/II/III/IV" v-model="input.comarkInput" /> -->
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <!-- <span class="label-apb">Ventilasi</span> -->
                                            <h1 style="font-weight: bold;">Ventilasi</h1>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Spontan" label="Spontan" class="p-0"
                                                                color="primary" square v-model="input.CBVentilasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Kendali" label="Kendali" class="p-0"
                                                                color="primary" square v-model="input.CBVentilasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column"
                                                    :class="input.CBVentilasi != 'Ventilator' ? 'is-12' : 'is-4'">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Ventilator" label="Ventilator"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBVentilasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <template v-if="input.CBVentilasi == 'Ventilator'">
                                                    <div class="column is-4">
                                                        <VField>
                                                            <VControl>
                                                                <VInput type="text" class="input" placeholder="TV"
                                                                    v-model="input.CBVentilasiVentilatorTV" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-4">
                                                        <VField>
                                                            <VControl>
                                                                <VInput type="text" class="input" placeholder="RR"
                                                                    v-model="input.CBVentilasiVentilatorRR" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </template>
                                                <div class="column is-6">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Lain-lain" label="Lain-lain" class="p-0"
                                                                color="primary" square v-model="input.CBVentilasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6" v-if="input.CBVentilasi == 'Lain-lain'">
                                                    <VField>
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.CBVentilasiVentilatorRR" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <!-- <span class="label-apb">Teknik Regional</span> -->
                                            <h1 style="font-weight: bold;">Teknik Regional</h1>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-12">
                                                    <VField vertical label="Tipe Daerah Pemasangan Jarum/No">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder="TV"
                                                                v-model="input.TXTRegionalPemasangan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <span class="label-apb">Kateter</span>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-4">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox true-value="Ya" label="Ya" class="p-0"
                                                                        color="primary" square v-model="input.CBRegional" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox true-value="Tidak" label="Tidak" class="p-0"
                                                                        color="primary" square v-model="input.CBRegional" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <VField vertical label="Obat Obatan">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.TXTRegionalObat" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <!-- <span class="label-apb">Hasil</span> -->
                                            <h1 style="font-weight: bold;">Hasil</h1>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Total blok" label="Total blok"
                                                                class="p-0" color="primary" square
                                                                v-model="input.CBHasil" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Partial" label="Partial" class="p-0"
                                                                color="primary" square v-model="input.CBHasil" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Gagal" label="Gagal" class="p-0"
                                                                color="primary" square v-model="input.CBHasil" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pl-3">
                                        <div class="column" style="overflow: auto;">
                                            <table class="tg">
                                                <thead>
                                                    <tr>
                                                        <th class="col-stuck" :colspan="jumlahIndexN2O + 3">
                                                            Obat / Waktu
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <VField class="is-flex">
                                                                <VControl>
                                                                    <VInput v-model="input['namaObatN2O_0']"
                                                                        placeholder="Nama Obat" type="text" />
                                                                </VControl>
                                                                <VControl>
                                                                    <VInput v-model="input['dosisObatN2O_0']"
                                                                        placeholder="Dosis" type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                        <td v-for="index in jumlahIndexN2O">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['waktuNamaObatN2O_0_' + index]"
                                                                        type="time" placeholder="Pick an hour" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                        <td>
                                                            <VControl>
                                                                <VInput v-model="input['jumlahObatN2O_0']"
                                                                    placeholder="Jumlah Obat" type="text"
                                                                    style="width: 100px;" class="my-auto" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <VField class="is-flex">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox true-value="N2O" label="N2O" color="primary"
                                                                        square v-model="input['oksigenObatN2O_0']" />
                                                                </VControl>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox true-value="O2" label="O2" color="primary"
                                                                        square v-model="input['oksigenObatN2O_0']" />
                                                                </VControl>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox true-value="Air" label="Air" color="primary"
                                                                        square v-model="input['oksigenObatN2O_0']" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                        <td v-for="index in jumlahIndexN2O">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['waktuObatN2O_0_' + index]"
                                                                        type="time" placeholder="Pick an hour" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                        <td>
                                                            <VControl>
                                                                <VInput v-model="input.jumlahOksigenObatN2O"
                                                                    placeholder="Jumlah Obat" type="text"
                                                                    style="width: 100px;" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <template v-for="indexnmObat in jumlahIndexN2ONamaObat">
                                                        <tr>
                                                            <td>
                                                                <VField class="is-flex">
                                                                    <VControl>
                                                                        <VInput
                                                                            v-model="input['namaObatN2O_' + indexnmObat + 1]"
                                                                            placeholder="Nama Obat" type="text" />
                                                                    </VControl>
                                                                    <VControl>
                                                                        <VInput
                                                                            v-model="input['dosisObatN2O_' + indexnmObat + 1]"
                                                                            placeholder="Dosis" type="text" />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td v-for="index in jumlahIndexN2O">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput
                                                                            v-model="input['waktuNamaObatN2O_' + indexnmObat + 1 + '_' + index]"
                                                                            type="time" placeholder="Pick an hour" />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VControl>
                                                                    <VInput
                                                                        v-model="input['jumlahObatN2O_' + indexnmObat + 1]"
                                                                        placeholder="Jumlah Obat" type="text"
                                                                        style="width: 100px;" />
                                                                </VControl>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <VField class="is-flex">
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox true-value="N2O" label="N2O"
                                                                            color="primary" square
                                                                            v-model="input['oksigenObatN2O_' + indexnmObat + 1]" />
                                                                    </VControl>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox true-value="O2" label="O2"
                                                                            color="primary" square
                                                                            v-model="input['oksigenObatN2O_' + indexnmObat + 1]" />
                                                                    </VControl>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox true-value="Air" label="Air"
                                                                            color="primary" square
                                                                            v-model="input['oksigenObatN2O_' + indexnmObat + 1]" />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td v-for="index in jumlahIndexN2O">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput
                                                                            v-model="input['waktuObatN2O_' + indexnmObat + 1 + '_' + index]"
                                                                            type="time" placeholder="Pick an hour" />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                            <td>
                                                                <VControl>
                                                                    <VInput v-model="input.jumlahOksigenObatN2O"
                                                                        placeholder="Jumlah Obat" type="text"
                                                                        style="width: 100px;" />
                                                                </VControl>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                    <tr>
                                                        <td>
                                                            <VField class="is-flex">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox true-value="isofluran" label="isofluran"
                                                                        color="primary" square
                                                                        v-model="input.namaGasObat" />
                                                                </VControl>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox true-value="sevofluran" label="sevofluran"
                                                                        color="primary" square
                                                                        v-model="input.namaGasObat" />
                                                                </VControl>
                                                            </VField>
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput v-model="input.namaGasObatPersentase"
                                                                        placeholder="....." type="text"
                                                                        style="width: 150px;" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>%</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                        <td v-for="index in jumlahIndexN2O">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['waktuGasObat_' + index]"
                                                                        type="time" placeholder="Pick an hour" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                        <td>
                                                            <VControl>
                                                                <VInput v-model="input.jumlahGasObat"
                                                                    placeholder="Jumlah Obat" type="text"
                                                                    style="width: 100px;" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="col-stuck">
                                                        <td class="col-stuck">
                                                            <VButton type="button" color="info" raised rounded
                                                                icon="feather:plus-circle" class="" @click="addN20Obat()">
                                                                Obat
                                                            </VButton>
                                                            <br>
                                                            <VButton type="button" color="danger" raised rounded
                                                                icon="feather:trash" class=""
                                                                @click="jumlahIndexN2ONamaObat > 0 ? jumlahIndexN2ONamaObat-- : jumlahIndexN2ONamaObat">
                                                                Hapus
                                                            </VButton>
                                                        </td>
                                                        <td class="col-stuck" style="left: 100px;">
                                                            <VButton type="button" color="info" raised rounded
                                                                icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                                @click="addN20()">
                                                                Waktu
                                                            </VButton>
                                                            <VButton type="button" color="danger" raised rounded
                                                                icon="feather:trash" class=" mr-3 mt-0 mb-0"
                                                                @click="jumlahIndexN2O > 1 ? jumlahIndexN2O-- : jumlahIndexN2O">
                                                                Hapus
                                                            </VButton>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pl-3">
                                        <div class="column" style="overflow: auto;">
                                            <table class="tg">
                                                <thead>
                                                    <tr>
                                                        <th width="10%" class="col-stuck">Waktu</th>
                                                        <th v-for="index in jumlahIndexN2OObat">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['waktuN20Obat_' + index]"
                                                                        type="time" placeholder="Pick an hour" />
                                                                </VControl>
                                                            </VField>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>Suhu</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexN2OObat">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['suhuN20Obat_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>TVS</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexN2OObat">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['tvsN20Obat_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>R</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexN2OObat">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['rN20Obat_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>N</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexN2OObat">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['nN20Obat_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>TD</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexN2OObat">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['tdN20Obat_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <!-- <VIconButton color="info" light raised circle
                                                        icon="feather:plus-circle" /> -->
                                                            <VButton type="button" color="info" raised rounded
                                                                icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                                @click="addN20(true)">
                                                                Tambah
                                                            </VButton>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pt-3">
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;" class="text-center">Grafik Tanda Vital</h1>
                                            <VCard style="border-radius: 16px;">
                                                <highcharts :options="chartOptions3"></highcharts>
                                            </VCard>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pt-3">
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;" class="text-center">Grafik Tekanan Darah</h1>
                                            <VCard style="border-radius: 16px;">
                                                <highcharts :options="chartOptionsTD3"></highcharts>
                                            </VCard>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pl-3">
                                        <div class="column" style="overflow: auto;">
                                            <h1>Pemantauan</h1>
                                            <table class="tg">
                                                <tbody>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas" style="width:100px">
                                                            <span>Sp02</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexPemantauan" style="width:100px">
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput v-model="input['sp02Pemantauan_' + index]"
                                                                        type="text" style="width: 100px;" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>%</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas" style="width:100px">
                                                            <span>PE CO2</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexPemantauan" style="width:100px">
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput v-model="input['peco2Pemantauan_' + index]"
                                                                        type="text" style="width: 100px;" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>mmHg</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas" style="width:100px">
                                                            <span>FiO2</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexPemantauan" style="width:100px">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['fi02Pemantauan_' + index]"
                                                                        type="text" style="width: 100px;" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas" style="width:100px">
                                                            <span>Tekanan Nafas</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexPemantauan" style="width:100px">
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput
                                                                        v-model="input['tekanannafasPemantauan_' + index]"
                                                                        type="text" style="width: 100px;" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>cmH2O</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas" style="width:100px">
                                                            <span>Cairan Infus</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexPemantauan" style="width:100px">
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput
                                                                        v-model="input['cairaninfusPemantauan_' + index]"
                                                                        type="text" style="width: 100px;" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>ml</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas" style="width:100px">
                                                            <span>Transfusi Darah</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexPemantauan" style="width:100px">
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput v-model="input['darahPemantauan_' + index]"
                                                                        type="text" style="width: 100px;" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>ml</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas" style="width:100px">
                                                            <span>Urine</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexPemantauan" style="width:100px">
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput v-model="input['urinePemantauan_' + index]"
                                                                        type="text" style="width: 100px;" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>ml</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas" style="width:100px">
                                                            <span>Darah</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexPemantauan" style="width:100px">
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput v-model="input['pendarahanPemantauan_' + index]"
                                                                        type="text" style="width: 100px;" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>ml</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <!-- <VIconButton color="info" light raised circle
                                                        icon="feather:plus-circle" /> -->
                                                            <VButton type="button" color="info" raised rounded
                                                                icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                                @click="addPemantauan()">
                                                                Tambah
                                                            </VButton>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold; margin-bottom: 1.5rem;">
                                                Keterangan
                                            </h1>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField vertical label="Induksi pukul">
                                                        <VControl>
                                                            <VInput type="time" class="input" placeholder=""
                                                                v-model="input.keteranganJamInduksi"
                                                                style="width: 100px !important" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField vertical label="Pasien siap insisi pukul">
                                                        <VControl>
                                                            <VInput type="time" class="input" placeholder=""
                                                                v-model="input.keteranganJamPasienSiapInsisi"
                                                                style="width: 100px !important" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField vertical label="Insisi mulai pukul">
                                                        <VControl>
                                                            <VInput type="time" class="input" placeholder=""
                                                                v-model="input.keteranganJamInsisiMulai"
                                                                style="width: 100px !important" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField vertical label="Anastesi selesai pukul">
                                                        <VControl>
                                                            <VInput type="time" class="input" placeholder=""
                                                                v-model="input.keteranganAnastesiSelesai"
                                                                style="width: 100px !important" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField vertical label="Ekstubasi pukul">
                                                        <VControl>
                                                            <VInput type="time" class="input" placeholder=""
                                                                v-model="input.keteranganJamEkstubasi"
                                                                style="width: 100px !important" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField vertical label="Insisi mulai pukul">
                                                        <VControl>
                                                            <VInput type="time" class="input" placeholder=""
                                                                v-model="input.keteranganJamInsisiMulai"
                                                                style="width: 100px !important" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField vertical label="Pasien keluar kamar operasi pukul">
                                                        <VControl>
                                                            <VInput type="time" class="input" placeholder=""
                                                                v-model="input.keteranganJamPasienKeluarKamar"
                                                                style="width: 100px !important" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-12">
                                                    <VField vertical label="Catatan">
                                                        <VControl>
                                                            <VTextarea v-model="input.keteranganCatatanInsisi" rows="3"
                                                                placeholder="" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-12">
                                                    <VField vertical label="Perubahan teknis anestesi">
                                                        <VControl>
                                                            <VTextarea v-model="input.keteranganTeknisAnestesi" rows="3"
                                                                placeholder="" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-6">
                                                    <VControl class="prime-auto">
                                                        <AutoComplete v-model="input.perawatAnestesiTeknis"
                                                            :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                            :field="'label'" class="mt-2" placeholder="Perawat Anestesi" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl class="prime-auto">
                                                        <AutoComplete v-model="input.dokterAnestesiTeknis"
                                                            :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" placeholder="Dokter Anestesi"
                                                            :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </VCard>
                            </div>
                        </Fieldset>
                    </div>

                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="Post Anastesi" class="is-warning">
                            <div class="column is-12">
                                <VCard>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <h1 style="font-weight: bold; margin-bottom: 1.5rem;">
                                            CATATAN RUANG PEMULIHAN
                                        </h1>
                                        <div class="column is-12">
                                            <VField vertical label="Jam keluar kamar operasi">
                                                <VControl>
                                                    <VInput type="time" class="input" placeholder=""
                                                        v-model="input.jamCatatanPemulihan"
                                                        style="width: 100px !important" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <span class="label-apb">Jalan Nafas</span>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Bersih dan Lapang"
                                                                label="Bersih dan Lapang" class="p-0" color="primary" square
                                                                v-model="input.jalanNafas" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <span class="label-apb">Pernafasan</span>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Spontan" label="Spontan" class="p-0"
                                                                color="primary" square v-model="input.pernafasan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Dibantu" label="Dibantu" class="p-0"
                                                                color="primary" square v-model="input.pernafasan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <span class="label-apb">Bila Spontan</span>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Adekuat bersuara"
                                                                label="Adekuat bersuara" class="p-0" color="primary" square
                                                                v-model="input.bilaSpontan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Penyumbat" label="Penyumbat" class="p-0"
                                                                color="primary" square v-model="input.bilaSpontan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Membutuhkan bantuan alat"
                                                                label="Membutuhkan bantuan alat" class="p-0" color="primary"
                                                                square v-model="input.bilaSpontan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline mt-2 pl-3">
                                        <div class="column is-12">
                                            <span class="label-apb">Kesadaran</span>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Sadar Betul" label="Sadar Betul"
                                                                class="p-0" color="primary" square
                                                                v-model="input.kesadaran" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Belum sadar betul"
                                                                label="Belum sadar betul" class="p-0" color="primary" square
                                                                v-model="input.kesadaran" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Tidur dalam" label="Tidur dalam"
                                                                class="p-0" color="primary" square
                                                                v-model="input.kesadaran" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="VAS" label="VAS" class="p-0"
                                                                color="primary" square v-model="input.kesadaran" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pl-3">
                                        <div class="column is-12">
                                            <!-- <span class="label-apb">Skor ALDRETE</span> -->
                                            <h1 style="font-weight: bold;">Skor ALDRETE</h1>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField label="Aktivitas">
                                                        <VControl>
                                                            <VInput type="number" class="input" placeholder=""
                                                                v-model="input.skorAktifitasPemulihan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField label="Sirkulasi">
                                                        <VControl>
                                                            <VInput type="number" class="input" placeholder=""
                                                                v-model="input.skorSirkulasiPemulihan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField label="Pernafasan">
                                                        <VControl>
                                                            <VInput type="number" class="input" placeholder=""
                                                                v-model="input.skorPernafasanPemulihan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField label="Kesadaran">
                                                        <VControl>
                                                            <VInput type="number" class="input" placeholder=""
                                                                v-model="input.skorKesadaranPemulihan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField label="Warna Kulit">
                                                        <VControl>
                                                            <VInput type="number" class="input" placeholder=""
                                                                v-model="input.skorWarnaKulitPemulihan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField label="Total">
                                                        <VControl>
                                                            <VInput type="number" class="input" placeholder=""
                                                                v-model="input.skorTotalPemulihan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pl-3">
                                        <div class="column" style="overflow: auto;">
                                            <table class="tg">
                                                <thead>
                                                    <tr>
                                                        <th class="col-stuck" :colspan="jumlahIndexObat + 3">
                                                            Obat / Waktu
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="indexnmObat in jumlahIndexNamaObat">
                                                        <td style="width: 100px;">
                                                            <VField class="is-flex">
                                                                <VControl>
                                                                    <VInput v-model="input['namaObat_' + indexnmObat]"
                                                                        placeholder="Nama Obat" type="text"
                                                                        style="width: 100px;" />
                                                                </VControl>
                                                                <VControl>
                                                                    <VInput v-model="input['dosisObat_' + indexnmObat]"
                                                                        placeholder="Dosis" type="text"
                                                                        style="width: 100px;" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                        <td v-for="index in jumlahIndexObat" style="width:100px">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['waktuObat_' + index]"
                                                                        type="time" placeholder="Pick an hour" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                        <td style="width:100px">
                                                            <VControl>
                                                                <VInput v-model="input.jumlahObat" placeholder="Jumlah Obat"
                                                                    type="text" style="width: 100px;" />
                                                            </VControl>
                                                        </td>
                                                        <td style="width:100px">
                                                            <VControl class="prime-auto" style="margin-top: -6px;">
                                                                <AutoComplete v-model="input.parafObat"
                                                                    :suggestions="d_Petugas"
                                                                    @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                                    :dropdown="true" :minLength="3" :appendTo="'body'"
                                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                    class="mt-2" placeholder="Nama & Paraf"
                                                                    style="width:150px" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <VField>
                                                                <VControl icon="fas fa-sticky-note" fullwidth
                                                                    class="prime-auto-select">
                                                                    <Dropdown v-model="input.ruteObat" :options="d_ruteObat"
                                                                        :optionLabel="'label'" style="width: 100%;"
                                                                        placeholder="Rute" :filter="true" showClear />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                        <td v-for="index in jumlahIndexObat" style="width:100px">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['waktuRuteObat_' + index]"
                                                                        type="time" placeholder="Pick an hour" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                        <td style="width:100px">
                                                            <VControl>
                                                                <VInput v-model="input.jumlahDosisObat"
                                                                    placeholder="Jumlah Obat" type="text"
                                                                    style="width: 100px;" />
                                                            </VControl>
                                                        </td>
                                                        <td style="width:100px">
                                                            <VControl class="prime-auto" style="margin-top: -6px;">
                                                                <AutoComplete v-model="input.parafRuteObat"
                                                                    :suggestions="d_Petugas"
                                                                    @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                                    :dropdown="true" :minLength="3" :appendTo="'body'"
                                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                    class="mt-2" placeholder="Nama & Paraf"
                                                                    style="width:150px" />
                                                            </VControl>
                                                        </td>
                                                    </tr>
                                                    <!-- <tr v-for="indexnmObat in jumlahIndexNamaObat">
                                                    <td style="width:100px">
                                                        <VField>
                                                            <VControl>
                                                                <VInput v-model="input['namaObat_' + indexnmObat]" placeholder="Nama Obat" type="text" style="width: 150px;"/>
                                                            </VControl>
                                                        </VField>
                                                    </td>
                                                    <td v-for="index in jumlahIndexObat" style="width:100px">
                                                        <VField>
                                                            <VControl>
                                                                <VInput v-model="input['waktuObat_'+ indexnmObat + '_' + index]" type="time"
                                                                    placeholder="Pick an hour" />
                                                            </VControl>
                                                        </VField>
                                                    </td>
                                                    <td style="width:100px">
                                                        <VControl>
                                                            <VInput v-model="input['jumlahObat_' + indexnmObat]" placeholder="Jumlah Obat" type="text" style="width: 100px;"/>
                                                        </VControl>
                                                    </td>
                                                    <td style="width:100px">
                                                        <VControl class="prime-auto" style="margin-top: -6px;">
                                                            <AutoComplete v-model="input['parafObat_' + indexnmObat]" :suggestions="d_Petugas"
                                                                @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                :field="'label'" class="mt-2" placeholder="Nama & Paraf" style="width:150px"/>
                                                        </VControl>
                                                    </td>
                                                </tr> -->
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td class="col-stuck">
                                                            <VButton type="button" color="info" raised rounded
                                                                icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                                @click="addIndexNamaObat()">
                                                                Obat
                                                            </VButton>
                                                            <VButton type="button" color="danger" raised rounded
                                                                icon="feather:trash" class=" mr-3 mt-0 mb-0"
                                                                @click="jumlahIndexNamaObat > 1 ? jumlahIndexNamaObat-- : jumlahIndexNamaObat">
                                                                Hapus
                                                            </VButton>
                                                        </td>
                                                        <td class="col-stuck" style="left: 100px">
                                                            <VButton type="button" color="info" raised rounded
                                                                icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                                @click="addIndexObat()">
                                                                Waktu
                                                            </VButton>
                                                            <VButton type="button" color="danger" raised rounded
                                                                icon="feather:trash" class=" mr-3 mt-0 mb-0"
                                                                @click="jumlahIndexObat > 1 ? jumlahIndexObat-- : jumlahIndexObat">
                                                                Hapus
                                                            </VButton>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pl-3">
                                        <div class="column" style="overflow: auto;">
                                            <table class="tg">
                                                <thead>
                                                    <tr>
                                                        <th width="10%" class="col-stuck">Waktu</th>
                                                        <th v-for="index in jumlahIndexVSObat">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['waktuVitalObat_' + index]"
                                                                        type="time" placeholder="Pick an hour" />
                                                                </VControl>
                                                            </VField>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>Suhu</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexVSObat">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['suhuVitalObat_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>TVS</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexVSObat">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['tvsVitalObat_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>R</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexVSObat">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['rVitalObat_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>N</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexVSObat">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['nVitalObat_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck bg-colatas">
                                                            <span>TD</span>
                                                        </td>
                                                        <td v-for="index in jumlahIndexVSObat">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input['tdVitalObat_' + index]"
                                                                        type="text" />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-stuck">
                                                            <!-- <VIconButton color="info" light raised circle
                                                        icon="feather:plus-circle" /> -->
                                                            <VButton type="button" color="info" raised rounded
                                                                icon="feather:plus-circle" class=" mr-3 mt-0 mb-0"
                                                                @click="addIndexObat(true)">
                                                                Tambah
                                                            </VButton>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pt-3">
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;" class="text-center">Grafik Tanda Vital Obat</h1>
                                            <VCard style="border-radius: 16px;">
                                                <highcharts :options="chartOptions2"></highcharts>
                                            </VCard>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pt-3">
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;" class="text-center">Grafik Tekanan Darah Obat
                                            </h1>
                                            <VCard style="border-radius: 16px;">
                                                <highcharts :options="chartOptionsTD2"></highcharts>
                                            </VCard>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline pl-3">
                                        <div class="column is-12 columns is-multiline">
                                            <div class="column is-4">
                                                <h1 style="font-weight: bold;">Keluar kamar putih :</h1>
                                            </div>
                                            <div class="column is-4">
                                                <VField>
                                                    <VControl>
                                                        <VInput v-model="input.keluarKamarPutih" type="time"
                                                            placeholder="Pick an hour" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox true-value="VAS" label="VAS" class="p-0 mt-2"
                                                            color="primary" square v-model="input.keluarKamarPutihVAS" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <!-- <span class="label-apb">Skor ALDRETE</span> -->
                                            <h1 style="font-weight: bold;">Skor ALDRETE</h1>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField label="Aktivitas">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.skorAktifitas" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField label="Sirkulasi">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.skorSirkulasi" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField label="Pernafasan">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.skorPernafasan" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField label="Kesadaran">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.skorKesadaran" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField label="Warna Kulit">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.skorWarnaKulit" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField label="Total">
                                                        <VControl>
                                                            <VInput type="text" class="input" placeholder=""
                                                                v-model="input.skorTotal" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <span class="label-apb">Ke</span>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Ruang Rawat" label="Ruang Rawat"
                                                                class="p-0" color="primary" square
                                                                v-model="input.KeluarKamarPutihKe" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="ICU" label="ICU" class="p-0"
                                                                color="primary" square v-model="input.KeluarKamarPutihKe" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox true-value="Langsung Pulang" label="Langsung Pulang"
                                                                class="p-0" color="primary" square
                                                                v-model="input.KeluarKamarPutihKe" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline">
                                                <div class="column is-12">
                                                    <VField label="Catatan ruang pemulihan">
                                                        <VControl>
                                                            <VTextarea v-model="input.KeluarKamarPutihCatatan" rows="3"
                                                                placeholder="" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline">
                                                <div class="column is-12">
                                                    <div>
                                                        <h1>Dokter Anestesi</h1>
                                                        <VControl class="prime-auto">
                                                            <AutoComplete v-model="input.dokterAnestesi"
                                                                :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                                                :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                :field="'label'" class="mt-2" />
                                                        </VControl>
                                                    </div>
                                                </div>
                                                <!-- <div class="column is-6">
                                                <div>
                                                    <h1>DPJP Anestesi</h1>
                                                    <VControl class="prime-auto">
                                                        <AutoComplete v-model="input.dpjpAnestesi" :suggestions="d_Dokter"
                                                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                            :field="'label'" class="mt-2" />
                                                    </VControl>
                                                </div>
                                            </div> -->
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;">Steward Score</h1>
                                            <div class="columns is-multiline pt-3">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-8">
                                                            <ul style="list-style-type:none; margin:8px">
                                                                <li style="margin:8px">
                                                                    1. Kesadaran
                                                                    <ul>
                                                                        <li>2 = Respon</li>
                                                                        <li>1 = Respon terhadap stimulus</li>
                                                                        <li>0 = Tidak respon terhadap stimulus</li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="column is-4 my-auto">
                                                            <VField>
                                                                <VControl>
                                                                    <VSelect v-model="input.stewardScoreKesadaran">
                                                                        <VOption value=""></VOption>
                                                                        <VOption :value="2">
                                                                            2
                                                                        </VOption>
                                                                        <VOption :value="1">
                                                                            1
                                                                        </VOption>
                                                                        <VOption :value="0">
                                                                            0
                                                                        </VOption>
                                                                    </VSelect>
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-8">
                                                            <ul style="list-style-type:none; margin:8px">
                                                                <li style="margin:8px">
                                                                    2. Jalan Nafas
                                                                    <ul>
                                                                        <li>2 = Aktif menangis/batuk</li>
                                                                        <li>1 = Dapat menjaga patensi jalan nafas</li>
                                                                        <li>0 = Perlu bantuan nafas</li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="column is-4 my-auto">
                                                            <VField>
                                                                <VControl>
                                                                    <VSelect v-model="input.stewardScoreNafas">
                                                                        <VOption value=""></VOption>
                                                                        <VOption :value="2">
                                                                            2
                                                                        </VOption>
                                                                        <VOption :value="1">
                                                                            1
                                                                        </VOption>
                                                                        <VOption :value="0">
                                                                            0
                                                                        </VOption>
                                                                    </VSelect>
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-8">
                                                            <ul style="list-style-type:none; margin:8px">
                                                                <li style="margin:8px">
                                                                    3. Gerakan
                                                                    <ul>
                                                                        <li>2 = Gerakan bertujuan</li>
                                                                        <li>1 = Gerakan tanpa tujuan</li>
                                                                        <li>0 = Tidak bergerak</li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="column is-4 my-auto">
                                                            <VField>
                                                                <VControl>
                                                                    <VSelect v-model="input.stewardScoreGerakan">
                                                                        <VOption value=""></VOption>
                                                                        <VOption :value="2">
                                                                            2
                                                                        </VOption>
                                                                        <VOption :value="1">
                                                                            1
                                                                        </VOption>
                                                                        <VOption :value="0">
                                                                            0
                                                                        </VOption>
                                                                    </VSelect>
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-8 my-auto">
                                                            <span>(Total skor lebih besar dari 5 untuk pemulang) Total Skor
                                                                :
                                                            </span>
                                                        </div>
                                                        <div class="column is-4 my-auto">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput v-model="input.stewardScoreTotal" type="text"
                                                                        placeholder="Total Skor" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;">BROMAGE SCORE</h1>
                                        </div>
                                        <div class="column is-6">
                                            <VField horizontal label="Total Bromage Score">
                                                <VControl>
                                                    <VInput v-model="input.bromageScoreTotal" type="number" />
                                                </VControl>
                                            </VField>
                                            <VField horizontal label="Pukul">
                                                <VControl>
                                                    <VInput v-model="input.bromageScorePukul" type="time" />
                                                </VControl>
                                            </VField>
                                            <VField horizontal label="Dokter Anastesi">
                                                <VControl class="prime-auto">
                                                    <AutoComplete v-model="input.petugasBromage" :suggestions="d_Dokter"
                                                        @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <div class="columns is-multiline">
                                                <div class="column" style="overflow: auto;">
                                                    <table class="tg" style="width: 100%;">
                                                        <thead>
                                                            <th style="width: 50px;"></th>
                                                            <th style="width: 50px;">Melipat lutut</th>
                                                            <th style="width: 50px;">Melipat jari</th>
                                                            <th style="width: 50px;">Score</th>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="text-center">
                                                                <td>Blok tidak</td>
                                                                <td>++</td>
                                                                <td>++</td>
                                                                <td>0</td>
                                                            </tr>
                                                            <tr class="text-center">
                                                                <td>Blok partian</td>
                                                                <td>+</td>
                                                                <td>++</td>
                                                                <td>1</td>
                                                            </tr>
                                                            <tr class="text-center">
                                                                <td>Blok hampir</td>
                                                                <td>-</td>
                                                                <td>++</td>
                                                                <td>2</td>
                                                            </tr>
                                                            <tr class="text-center">
                                                                <td>Blok lengkap</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>3</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </VCard>
                            </div>
                        </Fieldset>
                    </div>
                </div>
            </div>
        </template>
    </MasterEMR>
</template>

<script setup lang="ts">
import MasterEMR from './master-emr.vue'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/observasi-ruang-pemulihan'
import Dropdown from 'primevue/dropdown';
import Panel from 'primevue/panel';

const jumlahIndexVS = ref(5)
const jumlahIndexVSObat = ref(5)
const jumlahIndexObat = ref(5)
const jumlahIndexNamaObat = ref(1);
const jumlahIndexN2O = ref(5)
const jumlahIndexN2OObat = ref(5)
const jumlahIndexN2ONamaObat = ref(0);
const jumlahIndexPemantauan = ref(5)

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const d_Diagnosa: any = ref([])
let checklist = ref(EMR.checklist())
let checkbox = ref(EMR.checkbox())


const triggerAllData = async () => {
    if (masterRef.value) {
        let ss = await masterRef.value.loadRiwayat()
        if (ss != null) {
            input.value = ss
        }
    }
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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const sudahDisimpan: any = ref(false);
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Petugas: any = ref([])
const d_comark: any = ref([
    { label: "I", value: "I" },
    { label: "II", value: "II" },
    { label: "III", value: "III" },
    { label: "IV", value: "IV" },
])
const d_ruteObat: any = ref([
    { label: "IV", value: "IV" },
    { label: "IM", value: "IM" },
    { label: "SC", value: "SC" },
    { label: "PER RECTAL", value: "PER RECTAL" },
    { label: "SUB LINGUAL", value: "SUB LINGUAL" },
    { label: "PER VAGINAM", value: "PER VAGINAM" },
])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const lockedFormName = ref(props.FORM_NAME)
const lockedFormUrl = ref(props.FORM_URL)

const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
input.value.details = {
    listObat: [
        {
            no: 1,
            namaObat: null,
            jmlObat: null,
            waktuObat: Array.from({ length: 10 }, (_, e) => ({ no: e, waktu: null }))
        }
    ],
    premedikasi: [],
    premedikasiIM: [],
    premedikasiIV: [],
    induksiObat: [
        {
            no: 1,
            obat: null,
            dosis: null,
            jumlah: null
        }
    ],
    infusperifer: [
        {
            no: 1,
            tempat: null,
            ukuran: null
        }
    ]
}

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

let chartOptions1 = reactive({
    chart: {
        type: 'spline',
    },
    title: {
        text: ''
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: { enabled: true },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        },
        spline: {
            dataLabels: {
                enabled: true,
                // style: {
                //   fontSize: '20px'
                // },
            },
            enableMouseTracking: false
        }
    },
    series: []
});

let chartOptions2 = reactive({
    chart: {
        type: 'spline',
    },
    title: {
        text: ''
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: { enabled: true },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        },
        spline: {
            dataLabels: {
                enabled: true,
                // style: {
                //   fontSize: '20px'
                // },
            },
            enableMouseTracking: false
        }
    },
    series: []
});

let chartOptions3 = reactive({
    chart: {
        type: 'spline',
    },
    title: {
        text: ''
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: { enabled: true },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        },
        spline: {
            dataLabels: {
                enabled: true,
                // style: {
                //   fontSize: '20px'
                // },
            },
            enableMouseTracking: false
        }
    },
    series: []
});

let chartOptionsTD1 = reactive({
    chart: {
        type: 'spline',
    },
    title: {
        text: ''
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: { enabled: true },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        },
        spline: {
            dataLabels: {
                enabled: true,
                // style: {
                //   fontSize: '20px'
                // },
            },
            enableMouseTracking: false
        }
    },
    series: []
});

let chartOptionsTD2 = reactive({
    chart: {
        type: 'spline',
    },
    title: {
        text: ''
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: { enabled: true },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        },
        spline: {
            dataLabels: {
                enabled: true,
                // style: {
                //   fontSize: '20px'
                // },
            },
            enableMouseTracking: false
        }
    },
    series: []
});

let chartOptionsTD3 = reactive({
    chart: {
        type: 'spline',
    },
    title: {
        text: ''
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        }
    },
    legend: { enabled: true },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        },
        spline: {
            dataLabels: {
                enabled: true,
                // style: {
                //   fontSize: '20px'
                // },
            },
            enableMouseTracking: false
        }
    },
    series: []
});

const chartHigh = (e: any) => {
    let labels = []
    let seriesNadi = []
    let seriesSuhu = []
    let seriesPernafasan = []
    let seriesSaturasi = []
    let seriesTD = []
    // let seriesSaturasi =[]
    for (let x = 0; x < jumlahIndexVS.value; x++) {
        if (e['suhu_' + x.toString()] != undefined) {
            seriesSuhu.push(parseFloat(e['suhu_' + x.toString()]))
        }
        if (e['tvs_' + x.toString()] != undefined) {
            seriesNadi.push(parseFloat(e['tvs_' + x.toString()]))
        }
        if (e['r_' + x.toString()] != undefined) {
            seriesPernafasan.push(parseFloat(e['r_' + x.toString()]))
        }
        if (e['n_' + x.toString()] != undefined) {
            seriesSaturasi.push(parseFloat(e['n_' + x.toString()]))
        }
        if (e['waktu_' + x.toString()] != undefined) {
            labels.push(e['waktu_' + x.toString()])
        }
    }

    chartOptions1.xAxis.categories = labels;
    chartOptions1.series =
        [{
            name: 'Suhu',
            color: 'green',
            marker: {
                symbol: 'circle',
            },
            data: seriesSuhu
        }, {
            name: 'TVS',
            color: 'red',
            data: seriesNadi,
            marker: {
                symbol: 'circle',
            },
        }, {
            name: 'R',
            data: seriesPernafasan,
            color: 'yellow',
            marker: {
                symbol: 'circle',
            },
        },
        {
            name: 'N',
            data: seriesSaturasi,
            marker: {
                symbol: 'circle',
            },
        }
        ]
}

const chartHigh2 = (e: any) => {
    let labels = []
    let seriesNadi = []
    let seriesSuhu = []
    let seriesPernafasan = []
    let seriesSaturasi = []
    let seriesTD = []
    // let seriesSaturasi =[]
    for (let x = 0; x < jumlahIndexVSObat.value; x++) {
        if (e['suhuVitalObat_' + x.toString()] != undefined) {
            seriesSuhu.push(parseFloat(e['suhuVitalObat_' + x.toString()]))
        }
        if (e['tvsVitalObat_' + x.toString()] != undefined) {
            seriesNadi.push(parseFloat(e['tvsVitalObat_' + x.toString()]))
        }
        if (e['rVitalObat_' + x.toString()] != undefined) {
            seriesPernafasan.push(parseFloat(e['rVitalObat_' + x.toString()]))
        }
        if (e['nVitalObat_' + x.toString()] != undefined) {
            seriesSaturasi.push(parseFloat(e['nVitalObat_' + x.toString()]))
        }

        if (e['waktuVitalObat_' + x.toString()] != undefined) {
            labels.push(e['waktuVitalObat_' + x.toString()])
        }
    }

    chartOptions2.xAxis.categories = labels;
    chartOptions2.series =
        [{
            name: 'Suhu',
            color: 'green',
            lineWidth: 4,
            marker: {
                symbol: 'circle',
            },
            data: seriesSuhu
        }, {
            name: 'TVS',
            color: 'red',
            data: seriesNadi,
            marker: {
                symbol: 'circle',
            },
        }, {
            name: 'R',
            data: seriesPernafasan,
            color: 'yellow',
            marker: {
                symbol: 'circle',
            },
        },
        {
            name: 'N',
            data: seriesSaturasi,
            marker: {
                symbol: 'circle',
            },
        }
        ]
}

const chartHigh3 = (e: any) => {
    let labels = []
    let seriesNadi = []
    let seriesSuhu = []
    let seriesPernafasan = []
    let seriesSaturasi = []
    let seriesTD = []
    // let seriesSaturasi =[]
    for (let x = 0; x < jumlahIndexN2OObat.value; x++) {
        if (e['suhuN20Obat_' + x.toString()] != undefined) {
            seriesSuhu.push(parseFloat(e['suhuN20Obat_' + x.toString()]))
        }
        if (e['tvsN20Obat_' + x.toString()] != undefined) {
            seriesNadi.push(parseFloat(e['tvsN20Obat_' + x.toString()]))
        }
        if (e['rN20Obat_' + x.toString()] != undefined) {
            seriesPernafasan.push(parseFloat(e['rN20Obat_' + x.toString()]))
        }
        if (e['nN20Obat_' + x.toString()] != undefined) {
            seriesSaturasi.push(parseFloat(e['nN20Obat_' + x.toString()]))
        }

        if (e['waktuN20Obat_' + x.toString()] != undefined) {
            labels.push(e['waktuN20Obat_' + x.toString()])
        }
    }

    chartOptions3.xAxis.categories = labels;
    chartOptions3.series =
        [{
            name: 'Suhu',
            color: 'green',
            marker: {
                symbol: 'circle',
            },
            data: seriesSuhu
        }, {
            name: 'TVS',
            color: 'red',
            marker: {
                symbol: 'circle',
            },
            data: seriesNadi
        }, {
            name: 'R',
            data: seriesPernafasan,
            color: 'yellow',
            marker: {
                symbol: 'circle',
            },
        },
        {
            name: 'N',
            data: seriesSaturasi,
            marker: {
                symbol: 'circle',
            },
        }
        ]
}

const chartHigh1TD = (e: any) => {
    let labels = []
    let seriesDiastolik = []
    let seriesSistolik = []

    // let seriesSaturasi =[]
    for (let x = 0; x < jumlahIndexVS.value; x++) {
        if (e['td_' + x.toString()] != undefined) {
            let inputString = e['td_' + x.toString()];
            let resultArray = inputString.split('/').map(Number);
            seriesSistolik.push(parseFloat(resultArray[0]))
            seriesDiastolik.push(parseFloat(resultArray[1]))
        }
    }

    chartOptionsTD1.xAxis.categories = labels
    chartOptionsTD1.series =
        [{
            name: 'Sistolik',
            color: 'red',
            lineWidth: 4,
            marker: {
                radius: 4
            },
            data: seriesSistolik
        }, {
            name: 'Diastolik',
            color: 'blue',
            data: seriesDiastolik
        },]
}

const chartHigh2TD = (e: any) => {
    let labels = []
    let seriesDiastolik = []
    let seriesSistolik = []

    // let seriesSaturasi =[]
    for (let x = 0; x < jumlahIndexVSObat.value; x++) {
        if (e['tdVitalObat_' + x.toString()] != undefined) {
            let inputString = e['tdVitalObat_' + x.toString()];
            let resultArray = inputString.split('/').map(Number);
            seriesSistolik.push(parseFloat(resultArray[0]))
            seriesDiastolik.push(parseFloat(resultArray[1]))
        }
    }

    chartOptionsTD2.xAxis.categories = labels
    chartOptionsTD2.series =
        [{
            name: 'Sistolik',
            color: 'red',
            lineWidth: 4,
            marker: {
                radius: 4
            },
            data: seriesSistolik
        }, {
            name: 'Diastolik',
            color: 'blue',
            data: seriesDiastolik
        },]
}

const chartHigh3TD = (e: any) => {
    let labels = []
    let seriesDiastolik = []
    let seriesSistolik = []

    // let seriesSaturasi =[]
    for (let x = 0; x < jumlahIndexN2OObat.value; x++) {
        if (e['tdN20Obat_' + x.toString()] != undefined) {
            let inputString = e['tdN20Obat_' + x.toString()];
            let resultArray = inputString.split('/').map(Number);
            seriesSistolik.push(parseFloat(resultArray[0]))
            seriesDiastolik.push(parseFloat(resultArray[1]))
        }
    }

    chartOptionsTD3.xAxis.categories = labels
    chartOptionsTD3.series =
        [{
            name: 'Sistolik',
            color: 'red',
            lineWidth: 4,
            marker: {
                radius: 4
            },
            data: seriesSistolik
        }, {
            name: 'Diastolik',
            color: 'blue',
            data: seriesDiastolik
        },]
}

const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }

                chartHigh(response[0])
                chartHigh2(response[0])
                chartHigh3(response[0])
                chartHigh1TD(response[0])
                chartHigh2TD(response[0])
                chartHigh3TD(response[0])
                if (!input.value.details.premedikasi || input.value.details.premedikasi.length == 0) {
                    input.value.details.premedikasi = [];
                    input.value.details.premedikasi.push(
                        { oral: null, dosis: null, jam: null }
                    );
                }
                if (!input.value.details.premedikasiIM || input.value.details.premedikasiIM.length == 0) {
                    input.value.details.premedikasiIM = [];
                    input.value.details.premedikasiIM.push(
                        { im: null, dosis: null, jam: null }
                    );
                }
                if (!input.value.details.premedikasiIV || input.value.details.premedikasiIV.length == 0) {
                    input.value.details.premedikasiIV = [];
                    input.value.details.premedikasiIV.push(
                        { iv: null, dosis: null, jam: null }
                    );
                }
                if (!input.value.details.induksiObat || input.value.details.induksiObat.length == 0) {
                    input.value.details.induksiObat = [];
                    input.value.details.induksiObat.push(
                        { no: 1, obat: null, dosis: null, jumlah: null }
                    )
                }
                if (!input.value.details.infusperifer || input.value.details.infusperifer.length == 0) {
                    input.value.details.infusperifer = [];
                    input.value.details.infusperifer.push(
                        { no: 1, tempat: null, ukuran: null }
                    )
                }
            }
            else {
                setAutoFill()
            }
        })
}

const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    console.log("Pasien", props.pasien);
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['TTDpasien'] = H.tandaTangan().get('TTDpasien')

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
            loadRiwayat();
            sudahDisimpan.value = true;
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

const kembaliKeun = () => {
    window.history.back()
}
const setAutoFill = async () => {
    await useApi().get(
        "emr/auto-fill?nocmfk=" + ID_PASIEN +
        "&collection=VitalSign" +
        "&field=tekananDarah,pernapasan,SPO2,nadi"
    ).then((response) => {
        if (response != null && response.length > 0) {
            input.value.rr = response.pernapasan
            input.value.SpO2 = response.SPO2
            input.value.n = response.nadi
            input.value.td = response.tekananDarah
        }
    })
}

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name + '-' + route.params.index_tabs
        if (!sudahDisimpan.value) {
            console.log("DISIMPAN", sudahDisimpan.value);

            const konfirmasi = H.alert('warning', 'Belum Disimpan!!!');
            if (!konfirmasi) {
                return next(false);
            }
        }
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});

onMounted(() => {
    lockedFormName.value = props.FORM_NAME
    lockedFormUrl.value = props.FORM_URL
    setView()
    setAutoFill()
    loadRiwayat()
})

const addVS = () => {
    jumlahIndexVS.value = jumlahIndexVS.value + 1;
}

const addN20 = (obat = false) => {
    if (!obat) {
        jumlahIndexN2O.value = jumlahIndexN2O.value + 1;
    } else {
        jumlahIndexN2OObat.value = jumlahIndexN2OObat.value + 1;
    }
}

const addPemantauan = () => {
    jumlahIndexPemantauan.value = jumlahIndexPemantauan.value + 1;
}
const addIndexObat = (obat = false) => {
    if (!obat) {
        jumlahIndexObat.value = jumlahIndexObat.value + 1;
    } else {
        jumlahIndexVSObat.value = jumlahIndexVSObat.value + 1;
    }
}

const addN20Obat = () => {
    jumlahIndexN2ONamaObat.value = jumlahIndexN2ONamaObat.value + 1;
}


const addIndexNamaObat = () => {
    jumlahIndexNamaObat.value = jumlahIndexNamaObat.value + 1;
}

const addPremedikasiOral = (type = 'oral') => {
    if (type == 'oral') {
        input.value.details.premedikasi.push(
            { oral: null, dosis: null, jam: null }
        );
    } else if (type == 'IM') {
        input.value.details.premedikasiIM.push(
            { im: null, dosis: null, jam: null }
        );
    } else if (type == 'IV') {
        input.value.details.premedikasiIV.push(
            { iv: null, dosis: null, jam: null }
        );
    }
}

const removePremedikasiOral = (index, type = 'oral') => {
    if (type == 'oral') {
        input.value.details.premedikasi.splice(index, 1);
    } else if (type == 'IM') {
        input.value.details.premedikasiIM.splice(index, 1);
    } else if (type == 'IV') {
        input.value.details.premedikasiIV.splice(index, 1);
    }
}

const addnewInduksiObat = () => {
    input.value.details.induksiObat.push(
        {
            no: input.value.details.induksiObat[input.value.details.induksiObat.length - 1].no + 1,
            obat: null,
            dosis: null,
            jumlah: null,
        }
    )
}

const addnewInfusPerifer = () => {
    input.value.details.infusperifer.push(
        {
            no: input.value.details.infusperifer[input.value.details.infusperifer.length - 1].no + 1,
            tempat: null,
            ukuran: null,
        }
    )
}

watch(
    input,
    (newVal, oldVal) => {
        // console.log("watch triggered", newVal)
        let skorAPemulihan = 0;
        let skorA = 0;
        // Total Skor Aldrete Catatan Ruang Pemulihan
        if (input.value.skorAktifitasPemulihan) {
            skorAPemulihan += parseInt(input.value.skorAktifitasPemulihan)
        }
        if (input.value.skorSirkulasiPemulihan) {
            skorAPemulihan += parseInt(input.value.skorSirkulasiPemulihan)
        }
        if (input.value.skorPernafasanPemulihan) {
            skorAPemulihan += parseInt(input.value.skorPernafasanPemulihan)
        }
        if (input.value.skorKesadaranPemulihan) {
            skorAPemulihan += parseInt(input.value.skorKesadaranPemulihan)
        }
        if (input.value.skorWarnaKulitPemulihan) {
            skorAPemulihan += parseInt(input.value.skorWarnaKulitPemulihan)
        }
        input.value.skorTotalPemulihan = skorAPemulihan;
        // END TOTAL SKOR ALDRETE CATATAN RUANG PEMULIHAN

        // TOTAL SKOR ALDRETE
        if (input.value.skorAktifitas) {
            skorA += parseInt(input.value.skorAktifitas)
        }
        if (input.value.skorSirkulasi) {
            skorA += parseInt(input.value.skorSirkulasi)
        }
        if (input.value.skorPernafasan) {
            skorA += parseInt(input.value.skorPernafasan)
        }
        if (input.value.skorKesadaran) {
            skorA += parseInt(input.value.skorKesadaran)
        }
        if (input.value.skorWarnaKulit) {
            skorA += parseInt(input.value.skorWarnaKulit)
        }
        input.value.skorTotal = skorA;
        // END TOTAL SKOR ALDRETE

        // Total skor Steward
        let totalSteward = 0;
        if (input.value.stewardScoreKesadaran) {
            totalSteward += parseInt(input.value.stewardScoreKesadaran)
        }
        if (input.value.stewardScoreNafas) {
            totalSteward += parseInt(input.value.stewardScoreNafas)
        }
        if (input.value.stewardScoreGerakan) {
            totalSteward += parseInt(input.value.stewardScoreGerakan)
        }
        input.value.stewardScoreTotal = totalSteward;
        // END TOTAL SKOR STEWARD
    },
    { deep: true }
)
</script>
<style lang="scss">
.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 150%;
    // width: 100%;
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;

    // font-size: 14px;
    overflow: hidden;
    padding: 7px;
    word-break: normal;
}

.tg tr {
    height: 20px;
}

.tg th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    vertical-align: middle;
    // font-size: 14px;
    text-align: center !important;
    font-weight: bold;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.col-stuck {
    width: 150px;
    position: sticky;
    left: 0;
    z-index: 2;
    background-color: aliceblue;
    vertical-align: inherit;
}

.custom-fieldset {
    border: 1px solid;
    border-radius: 5px;
    border-color: var(--fade-grey-dark-2);
}

.custom-legenda {
    margin-left: 15px;
    font-weight: 500;
}

.field>label {
    overflow: hidden;
    width: 300px !important;
    height: 1.2rem;
    white-space: nowrap;
}
</style>