<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Triage Pasien Obstetri & Ginekologi IGD</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="true" isHideST></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column">
                    <div class="column columns is-multiline pb-0">
                        <div class="column is-3">
                            <h1>Tanggal Masuk & Jam Kedatangan</h1>
                            <VDatePicker v-model="input.DTTanggal" mode="datetime" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal..." v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-3">
                            <h1>Waktu Diperiksa Dokter</h1>
                            <VDatePicker v-model="input.TWaktuDiperiksaDokter" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:clock" fullwidth>
                                        <VInput :value="inputValue" placeholder="Waktu..." v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-3">
                            <h1>Dokter</h1>
                            <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter"
                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                        </div>
                        <div class="column is-3" style="text-align:center;">
                            <h1>Tanda Tangan</h1>
                            <TandaTangan :elemenID="'TTDPerawat'" :width="'150'" :height="'150'" class="dek" />
                        </div>
                    </div>

                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                    <div class="columns is-multiline column pb-0">
                        <div class="column is-12 pt-1 pb-1" style="font-size: large;">
                            <h1>Data Subjektif</h1>
                        </div>
                        <div class="column is-4">
                            <h1>Keluhan Utama :</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TAKeluhanUtama"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1>Riwayat Pengobatan :</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TARiwayatPengobatan"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1>Riwayat Alergi :</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TARiwayatAlergi"></VTextarea>
                            </VField>
                        </div>
                    </div>

                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                    <div class="columns is-multiline column pb-0">
                        <div class="column is-12 pt-1 pb-1" style="font-size: large;">
                            <h1>Data Objektif</h1>
                        </div>
                        <div class="column is-12" style="overflow: auto;">
                            <table class="tg">
                                <thead>
                                    <tr>
                                        <th>AIRWAY</th>
                                        <th>BREATHING</th>
                                        <th>CIRCULATION</th>
                                        <th>DISABILITY/NEUROLOGICAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width:15%">
                                            <div class="column" v-for="(data) in ListAirway">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        :true-value="data.caption" :label="data.caption"
                                                        v-model="input['CBairway']" />
                                                </VControl>
                                            </div>
                                        </td>
                                        <td style="width:15%">
                                            <div class="column" v-for="(data) in ListBreathing">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        :true-value="data.caption" :label="data.caption"
                                                        v-model="input['CBbreathing']" />
                                                </VControl>
                                            </div>
                                        </td>
                                        <td style="width:35%">
                                            <div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12" style="margin-bottom: -10px;">
                                                        <label style="font-weight: bold">Nadi</label>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="Kuat" label="Kuat" v-model="input.CBNadi" />
                                                        </VControl>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="Lemah" label="Lemah"
                                                                v-model="input.CBNadi" />
                                                        </VControl>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12" style="margin-bottom: -10px;">
                                                        <label style="font-weight: bold">CRT</label>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="< 2" label="< 2" v-model="input.CBCRT" />
                                                        </VControl>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="> 2" label="> 2" v-model="input.CBCRT" />
                                                        </VControl>
                                                    </div>
                                                </div>
                                                <div class="column">
                                                    <VField label="Warna Kulit">
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input.TBWarnakulit" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12" style="margin-bottom: -10px;">
                                                        <label style="font-weight: bold">Perdarahan</label>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="Terkontrol" label="Terkontrol"
                                                                v-model="input.CBPerdarahan" />
                                                        </VControl>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="Tidak terkontrol" label="Tidak terkontrol"
                                                                v-model="input.CBPerdarahan" />
                                                        </VControl>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="Tidak ada" label="Tidak ada"
                                                                v-model="input.CBPerdarahan" />
                                                        </VControl>
                                                    </div>
                                                </div>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12" style="margin-bottom: -10px;">
                                                        <label style="font-weight: bold">Turgor Kulit</label>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="Baik" label="Baik"
                                                                v-model="input.CBTurgorKulit" />
                                                        </VControl>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                true-value="Buruk" label="Buruk"
                                                                v-model="input.CBTurgorKulit" />
                                                        </VControl>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width:35%">
                                            <div class="columns is-multiline">
                                                <div class="column is-12" style="margin-bottom: -10px;">
                                                    <label style="font-weight: bold">Respon</label>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Alert"
                                                            label="Alert" v-model="input.CBRespon" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square true-value="Pain"
                                                            label="Pain" v-model="input.CBRespon" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Verbal" label="Verbal"
                                                            v-model="input.CBRespon" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Unrespon" label="Unrespon"
                                                            v-model="input.CBRespon" />
                                                    </VControl>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline">
                                                <div class="column is-12" style="margin-bottom: -10px;">
                                                    <label style="font-weight: bold">Pupil</label>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Isokor" label="Isokor"
                                                            v-model="input.CBPupil" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Anisokor" label="Anisokor"
                                                            v-model="input.CBPupil" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="Midriasis" label="Midriasis"
                                                            v-model="input.CBPupil" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            true-value="PinPont" label="PinPont"
                                                            v-model="input.CBPupil" />
                                                    </VControl>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline">
                                                <div class="column is-12" style="margin-bottom: -10px;">
                                                    <label style="font-weight: bold">Refleks : </label>
                                                </div>
                                                <div class="column is-5">
                                                    <VControl>
                                                        <VInput type="text" class="input" v-model="input.TB1Refelks" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-2"
                                                    style="text-align: center;vertical-align: middle;">/
                                                </div>
                                                <div class="column is-5">
                                                    <VControl>
                                                        <VInput type="text" class="input" v-model="input.TB2Refelks" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-12 pt-0">
                                                    <h1 class="pt-1 pb-1">GCS</h1>
                                                    <div class="columns">
                                                        <div class="column is-4">
                                                            <VField addons>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>E</VButton>
                                                                </VControl>
                                                                <VControl>
                                                                    <VInput type="text" class="input" maxLength="1"
                                                                        v-model="input.TBeGCS" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField addons>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>V</VButton>
                                                                </VControl>
                                                                <VControl>
                                                                    <VInput type="text" class="input" maxLength="1"
                                                                        v-model="input.TBvGCS" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField addons>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>M</VButton>
                                                                </VControl>
                                                                <VControl>
                                                                    <VInput type="text" class="input" maxLength="1"
                                                                        v-model="input.TBmGCS" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">
                                            Fetal
                                        </th>
                                        <th colspan="2"></th>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding: 7px;">
                                            <h1>DJJ</h1>
                                            <VField addons>
                                                <VControl>
                                                    <VInput type="text" class="input" v-model="input.TBdjjFetal" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>x/menit</VButton>
                                                </VControl>
                                            </VField>
                                        </td>
                                        <td colspan="2" style="padding: 7px;">
                                            <div class="columns is-multiline">
                                                <div class="column is-4">
                                                    <h1>T</h1>
                                                    <VField addons>
                                                        <VControl>
                                                            <VInput type="text" class="input" v-model="input.TBt_TTV" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>mmHg</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <h1>N</h1>
                                                    <VField addons>
                                                        <VControl>
                                                            <VInput type="text" class="input" v-model="input.TBn_TTV" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>x/menit</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <h1>R</h1>
                                                    <VField addons>
                                                        <VControl>
                                                            <VInput type="text" class="input" v-model="input.TBr_TTV" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>x/menit</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <h1>Sat O2</h1>
                                                    <VField addons>
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input.TBSat02_TTV" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>%</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <h1>Suhu Axila</h1>
                                                    <VField addons>
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input.TBSuhuAxila_TTV" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>°C</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <h1>Produksi Urine</h1>
                                                    <VField addons>
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input.TBproduksiUrine_TTV" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>ml/jam</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="padding: 7px;">
                                            <div class="columns">
                                                <div class="column is-4">
                                                    <h1>Nyeri :</h1>
                                                    <div class="columns column">
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Tidak" label="Tidak"
                                                                    v-model="input.CBNyeri_DO" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Ya" label="Ya"
                                                                    v-model="input.CBNyeri_DO" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4" v-if="input.CBNyeri_DO">
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input.TBLokasiNyeri" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-4">
                                                    <h1>Intensitas (0-10)</h1>
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.TBIntensitas" />
                                                    </VControl>
                                                </div>
                                                <div class="column is-4">
                                                    <h1>Jenis :</h1>
                                                    <div class="columns column">
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Akut" label="Akut"
                                                                    v-model="input.CBJenis_DO" />
                                                            </VControl>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="Kronis" label="Kronis"
                                                                    v-model="input.CBJenis_DO" />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="column is-12" style="overflow: auto;">
                            <table class="table" style="width: 150%;border-collapse:collapse;border: 1px solid black;">
                                <thead>
                                    <tr>
                                        <th style="width:15%;background-color: cyan;color:black">Kategori/Kriteria</th>
                                        <th style="width:15%;background-color: red;color:black">1 (Resusitative)</th>
                                        <th style="width:15%;background-color: orange;color:black">2 (Emergent)</th>
                                        <th style="width:15%;background-color: yellow;color:black">3 (Urgent)</th>
                                        <th style="width:15%;background-color: darkgreen;color:white">4 (Less Urgent)
                                        </th>
                                        <th style="width:15%;background-color: lightblue;color:black">5 (Non Urgent)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Asesment dan Terapi</td>
                                        <td style="background-color: red;text-align:center">Segera</td>
                                        <td style="background-color: orange;text-align:center">≤ 15 Menit</td>
                                        <td style="background-color: yellow;text-align:center">≤ 30 Menit</td>
                                        <td style="background-color: darkgreen;color:white;text-align: center;">≤ 60
                                            Menit</td>
                                        <td style="background-color: lightblue;text-align:center">≤ 120 Menit</td>
                                    </tr>
                                    <tr>
                                        <td>Penilaian ulang oleh bidan</td>
                                        <td style="background-color: red;text-align:center;color:white">Pengawasan terus
                                            menerus</td>
                                        <td style="background-color: orange;text-align:center;color:white">Setiap 15
                                            menit</td>
                                        <td style="background-color: yellow;text-align:center;color:white">Setiap 15
                                            menit</td>
                                        <td style="background-color: darkgreen;color:white;text-align:center">Setiap 30
                                            menit</td>
                                        <td style="background-color: lightblue;text-align:center">Setiap 60 menit</td>
                                    </tr>
                                    <tr>
                                        <td>Persalinan/ Ketuban</td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Pembukaan Lengkap" label="Pembukaan 8cm - lengkap"
                                                    v-model="input.CBResusitative" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Suspek Persalinan"
                                                    label="Suspek Persalinan Preterm / PPROM umur kehamilan <37 minggu)"
                                                    v-model="input.CBEmergent" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Tanda Persalinan"
                                                    label="Tanda persalinan fase aktif > 37 minggu"
                                                    v-model="input.CBUrgent" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Tanda Persalinan Laten"
                                                    label="Tanda persalinan fase laten/Tanda pecah ketuban >37 minggu"
                                                    v-model="input.CBLessUrgent" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Tidak Fase Persalinan"
                                                    label="Tidak dalam fase persalinan" v-model="input.CBNonUrgent" />
                                            </VControl>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Perdarahan</td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Perdarahan per vaginam 500ml/ 30 menit dengan / tanpa nyeri perut"
                                                    label="Perdarahan per vaginam 500ml/ 30 menit dengan / tanpa nyeri perut"
                                                    v-model="input.CBResusitative" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Kehamilan ektopik terganggu"
                                                    label="Kehamilan ektopik terganggu"
                                                    v-model="input.CBResusitative" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Inversio uteri akut" label="Inversio uteri akut"
                                                    v-model="input.CBResusitative" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Perdarahan disertai keram <37 minggu"
                                                    label="Perdarahan disertai keram <37 minggu"
                                                    v-model="input.CBEmergent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Kehamilan ektopik" label="Kehamilan ektopik"
                                                    v-model="input.CBEmergent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Perdarahan"
                                                    label="Perdarahan" v-model="input.CBEmergent" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Perdarahan disertai keram >37 minggu"
                                                    label="Perdarahan disertai keram >37 minggu"
                                                    v-model="input.CBUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Perdarahan trimester pertama tanpa nyeri perut"
                                                    label="Perdarahan trimester pertama tanpa nyeri perut"
                                                    v-model="input.CBUrgent" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Bercak Darah"
                                                    label="Bercak Darah" v-model="input.CBLessUrgent" />
                                            </VControl>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Hipertensi</td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Kejang"
                                                    label="Kejang" v-model="input.CBResusitative" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Hipertensi (TD >160/110mmHg) dan atau sakit kepala menetap, gangguan visus, nyeri ulu hati"
                                                    label="Hipertensi (TD >160/110mmHg) dan atau sakit kepala menetap, gangguan visus, nyeri ulu hati"
                                                    v-model="input.CBEmergent" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Hipertensi Ringan 140/90-160/110 mmHg dengan /tanpa keluhan subjektif"
                                                    label="Hipertensi Ringan 140/90-160/110 mmHg dengan /tanpa keluhan subjektif"
                                                    v-model="input.CBUrgent" />
                                            </VControl>
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Penilaian Janin</td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="DJJ bradikardia (<100dpm pada UK>38mgg; <120dpm pada UK<38mgg)"
                                                    label="DJJ bradikardia (<100dpm pada UK>38mgg; <120dpm pada UK<38mgg)"
                                                    v-model="input.CBResusitative" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Denyut jantung janin takikardia (>160dpm)"
                                                    label="Denyut jantung janin takikardia (>160dpm)"
                                                    v-model="input.CBEmergent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Oligohidramnion" label="Oligohidramnion"
                                                    v-model="input.CBEmergent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Penurunan gerak janin" label="Penurunan gerak janin"
                                                    v-model="input.CBEmergent" />
                                            </VControl>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Lain-lain</td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Nyeri perut berat akut" label="Nyeri perut berat akut"
                                                    v-model="input.CBResusitative" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Penurunan kesadaran" label="Penurunan kesadaran"
                                                    v-model="input.CBResusitative" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Prolaps tali pusat" label="Prolaps tali pusat"
                                                    v-model="input.CBResusitative" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Distress Napas Berat" label="Distress Napas Berat"
                                                    v-model="input.CBResusitative" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Suspek Sepsis"
                                                    label="Suspek Sepsis" v-model="input.CBResusitative" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Trauma mayor"
                                                    label="Trauma mayor" v-model="input.CBEmergent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Sesak napas"
                                                    label="Sesak napas" v-model="input.CBEmergent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Partus Rujukan Bidan / Dukun"
                                                    label="Partus Rujukan Bidan / Dukun" v-model="input.CBEmergent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Paska kemoterapi" label="Paska kemoterapi"
                                                    v-model="input.CBEmergent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Immunocompromised" label="Immunocompromised"
                                                    v-model="input.CBEmergent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Oligouria"
                                                    label="Oligouria" v-model="input.CBEmergent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Torsi kista"
                                                    label="Torsi kista" v-model="input.CBEmergent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Anaphylaxis"
                                                    label="Anaphylaxis" v-model="input.CBEmergent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Nyeri Berat(8-10)" label="Nyeri Berat(8-10)"
                                                    v-model="input.CBEmergent" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Nyeri abdomen/ punggung yang lebih berat dalam kehamilan"
                                                    label="Nyeri abdomen/ punggung yang lebih berat dalam kehamilan"
                                                    v-model="input.CBUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Nyeri pinggang /hematuria"
                                                    label="Nyeri pinggang /hematuria" v-model="input.CBUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Mual/muntah dan/atau diare dengan suspek dehidrasi"
                                                    label="Mual/muntah dan/atau diare dengan suspek dehidrasi"
                                                    v-model="input.CBUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Nyeri akut sedang (4-7)" label="Nyeri akut sedang (4-7)"
                                                    v-model="input.CBUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Dialisis"
                                                    label="Dialisis" v-model="input.CBUrgent" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Penilaian lanjutan dari poliklinik (hipertensi, DL)"
                                                    label="Penilaian lanjutan dari poliklinik (hipertensi, DL)"
                                                    v-model="input.CBLessUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Trauma minor (kecelakaan lalu lintas ringan/jatuh)"
                                                    label="Trauma minor (kecelakaan lalu lintas ringan/jatuh)"
                                                    v-model="input.CBLessUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Mual / muntah dan/ diare"
                                                    label="Mual / muntah dan/ diare" v-model="input.CBLessUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Tanda infeksi (disuria, batuk, demam, menggigil)"
                                                    label="Tanda infeksi (disuria, batuk, demam, menggigil)"
                                                    v-model="input.CBLessUrgent" />
                                            </VControl>
                                        </td>
                                        <td>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Hal-hal yang tidak menimbulkan ancaman bagi ibu atau fetus"
                                                    label="Hal-hal yang tidak menimbulkan ancaman bagi ibu atau fetus"
                                                    v-model="input.CBNonUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Pemberian pematangan serviks"
                                                    label="Pemberian pematangan serviks" v-model="input.CBNonUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Plasenta previa tanpa indikasi rawat inap"
                                                    label="Plasenta previa tanpa indikasi rawat inap"
                                                    v-model="input.CBNonUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="ANC"
                                                    label="ANC" v-model="input.CBNonUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Rencana Versi"
                                                    label="Rencana Versi" v-model="input.CBNonUrgent" />
                                            </VControl>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Rashes"
                                                    label="Rashes" v-model="input.CBNonUrgent" />
                                            </VControl>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="column is-12" style="text-align: right;font-style: italic;">
                            <p>
                                Adopted from Obstetrical Triage Acuity Scale of London Health Sciences Centre and The
                                Canadian Emergency
                                Department Triage and Acuity Scale,<br>
                                Modified by Division Maternal Fetal Medicine of Obstetric and Gynecology Department of
                                Medical Faculty
                                Udayana University/Sanglah Hospital
                            </p>
                        </div>

                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>

                        <div class="column is-12">
                            <h1>Kategori Triage</h1>
                            <div class="columns is-multiline mt-1" style="margin-left: 8px;">
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="1 (Segera)"
                                            label="1 (Segera)" v-model="input.CBKT" disabled/>
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="2 (10 Menit)"
                                            label="2 (10 Menit)" v-model="input.CBKT" disabled/>
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="3 (30 Menit)"
                                            label="3 (30 Menit)" v-model="input.CBKT" disabled/>
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="4 (60 Menit)"
                                            label="4 (60 Menit)" v-model="input.CBKT" disabled/>
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="5 (120 Menit)"
                                            label="5 (120 Menit)" v-model="input.CBKT" disabled/>
                                    </VControl>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>

                        <div class="column is-12">
                            <h1>Disposisi</h1>
                            <div class="columns is-multiline mt-1">
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Resuscitation Call"
                                            label="Resuscitation Call" v-model="input.CBResuscitationCall" />
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Green Code"
                                            label="Green Code" v-model="input.CBGreenCode" />
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            true-value="Resusitasi Intrauterin" label="Resusitasi Intrauterin"
                                            v-model="input.CBResusitasiIntrauterin" />
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="OHDU" label="OHDU"
                                            v-model="input.CBOHDU" />
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="ICU" label="ICU"
                                            v-model="input.CBICU" />
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ruang Isolasi"
                                            label="Ruang Isolasi" v-model="input.CBRuangIsolasi" />
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="VK" label="VK"
                                            v-model="input.CBVK" />
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ruang Tindakan"
                                            label="Ruang Tindakan" v-model="input.CBRuangTindakan" />
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ruang" label="Ruang"
                                            v-model="input.CBRuangan" />
                                    </VControl>
                                </div>
                                <div class="column is-2" v-if="input.CBRuangan">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBRuangan"
                                            placeholder="Ruangan..." />
                                    </VControl>
                                </div>
                                <div class="column is-2">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Boleh Pulang"
                                            label="Boleh Pulang" v-model="input.CBBolehPulang" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

useHead({ title: 'Triage Pasien Obstetri & Ginekologi IGD - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const user = useUserSession().getUser().pegawai;
const pasien: any = ref({})
const dataTTD: any = ref([])
const loadData: any = ref(true)
const d_Dokter: any = ref([])
const isLoading = ref(false)
const isAktive = ref()
const router = useRouter()
const selectedRegistrasi: any = ref({})
const COLLECTION: any = ref('TriagePasienObstetriGinekologiIGD') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})

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
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    date: {
        tanggal: new Date,
        jam: new Date
    },
    airway: [],
    disability: []
})
const input: any = ref({
    DTTanggal: new Date(),
    TWaktuDiperiksaDokter: new Date(),
    DDDokter: { label: user.namaLengkap, value: user.id }
})
const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
        d_Dokter.value = response
    })
}
const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter)
    } else {
        input.value.TBeGCS = 4
        input.value.TBvGCS = 5
        input.value.TBmGCS = 6
    }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
    object.pasien = H.setObjectPasien(pasien.value)
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
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        await fetchPasien()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
        loadData.value = false
    } catch (error) {
        console.error('Error mount cache TAB EMR:', error);
    }
});

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});

watch(() => [input.value['CBResusitative'], input.value['CBEmergent'], input.value['CBUrgent'], input.value['CBLessUrgent'], input.value['CBNonUrgent']], ([a, b, c, d, e]) => {
    //? Automate Checkbox
    if (a != false && a != undefined) {
        input.value.CBKT = "1 (Segera)"
    } else if (b != false && b != undefined) {
        input.value.CBKT = "2 (10 Menit)"
    } else if (c != false && c != undefined) {
        input.value.CBKT = "3 (30 Menit)"
    } else if (d != false && d != undefined) {
        input.value.CBKT = "4 (60 Menit)"
    } else if (e != false && e != undefined) {
        input.value.CBKT = "5 (120 Menit)"
    } else {
        input.value.CBKT = null
    }
});

// ===== ARRAY =====
let ListAirway = ref([
    { caption: "Bebas" },
    { caption: "Gargling" },
    { caption: "Stridor" },
    { caption: "Wheezing" },
    { caption: "Ronchi" },
    { caption: "Terintubasi" }
])
let ListBreathing = ref([
    { caption: "Spontan" },
    { caption: "Tachipneu" },
    { caption: "Dispneu" },
    { caption: "Apneu" },
    { caption: "Ventilasi Mekanik" },
    { caption: "Memakai Ventilator" }
])
const d_tidakYa: any = ref([
    { value: 1, label: 'Tidak' },
    { value: 2, label: 'Ya' }
])
const d_yaTidak: any = ref([
    { value: 1, label: 'Ya' },
    { value: 2, label: 'Tidak' }
])
const d_tidakAda_ada: any = ref([
    { value: 1, label: 'Tidak' },
    { value: 2, label: 'Ya' }
])
const d_penurunanbb: any = ref([
    { value: 1, label: 'Tidak' },
    { value: 2, label: 'Tidak Yakin' }
]);
const d_penurunannafsu: any = ref([
    { value: 1, label: 'Ya' },
    { value: 2, label: 'Tidak' }
]);
const d_penurunanbbYa: any = ref([
    { value: 3, label: '1-5 kg' },
    { value: 4, label: '6-10 kg' },
    { value: 5, label: '11-15 kg' },
    { value: 6, label: '>15 kg' }
]);
const d_mengontrolbab: any = ref([
    { value: 1, label: 'Inkontinen/tidak teratur (perlu enema)' },
    { value: 2, label: 'Kadang inkontinen (1xseminggu)' },
    { value: 3, label: 'Kontinen teratur' }
]);

const d_mengontrolbak: any = ref([
    { value: 1, label: 'Inkontinen/pakai kateter dan tidak terkontrol' },
    { value: 2, label: 'Kadang inkontinen (max 1x24 jam)' },
    { value: 3, label: 'Mandiri' }
]);
const d_bersihdiri: any = ref([
    { value: 1, label: 'Butuh pertolongan orang lain' },
    { value: 2, label: 'Mandiri' }
]);
const d_toilet: any = ref([
    { value: 1, label: 'Tergantung pertolongan orang lain' },
    { value: 2, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' },
    { value: 3, label: 'Mandiri' }
]);
const d_makan: any = ref([
    { value: 1, label: 'Tidak mampu' },
    { value: 2, label: 'Perlu seseorang menolong memotong makanan' },
    { value: 3, label: 'Mandiri' }
]);
const d_berpindahtt: any = ref([
    { value: 1, label: 'Tidak Mampu' },
    { value: 2, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' },
    { value: 3, label: 'Bantuan 1 orang' },
    { value: 4, label: 'Mandiri' }
]);
const d_mobilisasi: any = ref([
    { value: 1, label: 'Tidak Mampu' },
    { value: 2, label: 'Dengan kursi roda' },
    { value: 3, label: 'Bantuan 1 orang' },
    { value: 4, label: 'Mandiri' }
]);
const d_berpakaian: any = ref([
    { value: 1, label: 'Tergantung orang lain' },
    { value: 2, label: 'Sebagian dibantu (misal mengancing baju)' },
    { value: 3, label: 'Mandiri' }
]);
const d_tangga: any = ref([
    { value: 1, label: 'Tidak Mampu' },
    { value: 2, label: 'Butuh Pertolongan' },
    { value: 3, label: 'Mandiri' }
]);
const d_mandi: any = ref([
    { value: 1, label: 'Teragantung orang lain' },
    { value: 2, label: 'Mandiri' }
]);
const d_keadaanumum: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Sedang' },
    { value: 3, label: 'Lemah' },
    { value: 4, label: 'Jelek' }
])
const d_rujukan: any = ref([
    { value: 1, label: 'Ya' },
    { value: 2, label: 'Tidak' },
    { value: 3, label: 'Datang Sendiri' },
    { value: 4, label: 'Diantar' }
])
const d_allo: any = ref([
    { value: 1, label: 'Suami/Istri' },
    { value: 2, label: 'Orang Tua' },
    { value: 3, label: 'Anak' },
    { value: 4, label: 'Pasien' },
    { value: 5, label: 'Lainnya' }
])
const d_kualitasNyeri_AN: any = ref([
    { value: 1, label: 'Tumpul' },
    { value: 2, label: 'Tajam' },
    { value: 3, label: 'Panas/terbakar' },
    { value: 4, label: 'Lain-lain' }
])
const d_frekuensiNyeri_AN: any = ref([
    { value: 1, label: 'Jarang' },
    { value: 2, label: 'Hilang timbul' },
    { value: 3, label: 'Terus menerus' }
])
const d_kondisiPsikologis: any = ref([
    { value: 1, label: 'Gelisah' },
    { value: 2, label: 'Takut' },
    { value: 3, label: 'Sedih' },
    { value: 4, label: 'Rendah diri' },
    { value: 5, label: 'Acuh tak acuh' },
    { value: 6, label: 'Mudah tersinggung' },
    { value: 7, label: 'Menarik diri' }
])
const d_statusPernikahan: any = ref([
    { value: 1, label: 'Singel' },
    { value: 2, label: 'Menikah' },
    { value: 3, label: 'Bercerai' }
])
const d_pembiayaanKesehatan: any = ref([
    { value: 1, label: 'Biaya sendiri/keluarga' },
    { value: 2, label: 'Asuransi lainnya' }
])
const d_dukunganSosial: any = ref([
    { value: 1, label: 'Suami' },
    { value: 2, label: 'Orang tua' },
    { value: 3, label: 'Keluarga' },
    { value: 4, label: 'Lainnya' }
])
const d_kebiasaanIbu: any = ref([
    { value: 1, label: 'Merokok' },
    { value: 2, label: 'Minum alkohol' },
    { value: 3, label: 'Lainnya' }
])
const d_riwayatG: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada' }
])
const d_rpp = ref([
    { value: 1, label: 'Perlu' },
    { value: 2, label: 'Tidak Perlu' }
]);
// === Bikin Sendiri ===
const d_teraturSiklus: any = ref([
    { value: 1, label: 'Teratur' },
    { value: 2, label: 'Tidak Teratur' }
])
const d_ANC: any = ref([
    { value: 1, label: 'Dokter Kandungan' },
    { value: 2, label: 'Dokter Umum' },
    { value: 3, label: 'Bidan' },
    { value: 4, label: 'Lainnya' }
])
const d_frekuensi: any = ref([
    { value: 1, label: '1x' },
    { value: 2, label: '2x' },
    { value: 3, label: '3x' },
    { value: 4, label: '4x' }
])
const d_KSH: any = ref([
    { value: 1, label: 'Mual' },
    { value: 2, label: 'Muntah' },
    { value: 3, label: 'Perdarahan' },
    { value: 4, label: 'Pusing' },
    { value: 5, label: 'Sakit Kepala' },
    { value: 6, label: 'Lainnya' }
])
const d_RPK: any = ref([
    { value: 1, label: 'Hipertensi' },
    { value: 2, label: 'HIV' },
    { value: 3, label: 'Kencing Manis' },
    { value: 4, label: 'Jantung' },
    { value: 5, label: 'Jiwa' },
    { value: 6, label: 'Varises' },
    { value: 7, label: 'Lain-lain' },
])
const d_masalahPerkemihan: any = ref([
    { value: 1, label: 'Retensi Urine' },
    { value: 2, label: 'Inkontinensia Urine' },
    { value: 3, label: 'Dialysis' },
    { value: 4, label: 'Lainnya' }
])
const d_warnaUrine: any = ref([
    { value: 1, label: 'Kuning Jernih' },
    { value: 2, label: 'Keruh' },
    { value: 3, label: 'Kemerahan' }
]);
const d_masalahDefekasi: any = ref([
    { value: 1, label: 'Stoma' },
    { value: 2, label: 'Atresia ani' },
    { value: 3, label: 'Konstipasi' },
    { value: 4, label: 'Diare' },
    { value: 5, label: 'Inkontinensia alvi' },
    { value: 6, label: 'Lainnya' }
]);
const d_warnaFaeces: any = ref([
    { value: 1, label: 'Kuning' },
    { value: 2, label: 'Kecoklatan' },
    { value: 3, label: 'Kehitaman' },
    { value: 4, label: 'Perdarahan' }
]);
const d_masalahPernikahan: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada' }
])
const d_mengalamiKekerasanFisik: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada' }
])
</script>


<style lang="scss">
h1 {
    font-weight: bold !important;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100% !important;
}

.tg td {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    text-align: center !important;
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: bold;
    overflow: hidden;
    background-color: aquamarine;
    vertical-align: middle;
    padding: 10px 5px;
    word-break: normal;
}

.table {
    border-collapse: collapse !important;
    width: 150% !important;
}

.table th {
    text-align: center !important;
    border: 1px solid black !important;
    border-bottom: none;
}

.table td {
    border: 1px solid black !important;
    color: black !important;
    vertical-align: top !important;
    // text-align: center !important;
}
</style>
