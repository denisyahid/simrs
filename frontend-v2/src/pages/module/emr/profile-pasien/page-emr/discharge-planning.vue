<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                                :disabled="!NOREC_EMRPASIEN ? true : false" @click="print()"> Cetak
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoading" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">Data MRS</h1>
                    </div>
                    <div class="column is-2" style="margin-top: 10px;">
                        <h1 style="font-weight: bold;">Diagnosa Medis :</h1>
                    </div>
                    <div class="column is-5">
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="input.diagnosaMasuk" placeholder="Diagnosa Medis" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-2" style="margin-top: 10px;">
                        <h1 style="font-weight: bold;">Tanggal / Jam MRS :</h1>
                    </div>
                    <div class="column is-5">
                        <VField>
                            <VDatePicker v-model="input.tanggalKedatangan" mode="date" style="width: 100%"
                                trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal/Jam MRS"
                                                v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <div class="column is-1" style="margin-top: 10px;"></div>
                    <div class="column is-1" style="margin-top: 10px;">
                        <h1 style="font-weight: bold;">Jam :</h1>
                    </div>
                    <div class="columns pt-3 pb-0">
                        <div class="column is-12">
                            <VField>
                                <VDatePicker v-model="input.tanggalKedatangan" mode="Time" style="width: 100%"
                                    trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="WIB"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-2" style="margin-top: 10px;">
                        <h1 style="font-weight: bold;">Alasan MRS :</h1>
                    </div>
                    <div class="column is-5">
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="input.AlasanMrs" placeholder="Alasan MRS" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-5" style="margin-top: 10px;">
                        <h1 style="font-weight: bold;">Tanggal / Jam Dilakukan Perencanaan Pemulangan Pasien :</h1>
                    </div>
                    <div class="columns pt-3 pb-0">
                        <div class="column is-12">
                            <VField>
                                <VDatePicker v-model="input.tanggalKeluar" mode="date" style="width: 100%"
                                    trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal Keluar"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                    </div>
                    <div class="column is-1" style="margin-top: 10px;"></div>
                    <div class="column is-1" style="margin-top: 10px;margin-left: -40px;">
                        <h1 style="font-weight: bold;">Jam :</h1>
                    </div>
                    <div class="columns pt-3 pb-0">
                        <div class="column is-12">
                            <VField>
                                <VDatePicker v-model="input.tanggalKeluar" mode="Time" style="width: 100%"
                                    trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="WIB"
                                                    v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-4" style="margin-top: 10px;">
                        <h1 style="font-weight: bold;">Estimasi Tanggal Pemulangan Pasien:</h1>
                    </div>
                    <div class="column is-5">
                        <VField>
                            <VControl>
                                <VInput type="text" v-model="input.EstimasiTanggalPemulangan" placeholder="Estimasi Tanggal Pemulangan Pasien" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-12">
                    <table border="1" width="100%">
                        <tr>
                            <td colspan="2">
                                <h1 style="background-color: lightblue;font-weight: bold;">Kriteria Perencanaan Pemulangan Pasien yang dipenuhi*:</h1>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-height"><h1>1. Usia lanjut (> 60 tahun) dengan gangguan daya ingat</h1></td>
                            <td class="td-height">
                                <div class="columns is-multiline">
                                    <div class="column is-4" style="margin-top: 10px;margin-left: 10px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Jelaskan1"
                                            label="Jelaskan"
                                            v-model="input.Jelaskan1"
                                        />
                                    </VControl>
                                    </div>
                                    <div class="column is-7">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input.TBJelaskan1"  />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-height"><h1>2. Bayi BBLR</h1></td>
                            <td class="td-height">
                                <div class="columns is-multiline">
                                    <div class="column is-4" style="margin-top: 10px;margin-left: 10px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Jelaskan2"
                                            label="Jelaskan"
                                            v-model="input.Jelaskan2"
                                        />
                                    </VControl>
                                    </div>
                                    <div class="column is-7">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input.TBJelaskan2"  />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-height"><h1>3. Keterbatasan / gangguan mobilitas</h1></td>
                            <td class="td-height">
                                <div class="columns is-multiline">
                                    <div class="column is-4" style="margin-top: 10px;margin-left: 10px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Jelaskan3"
                                            label="Jelaskan"
                                            v-model="input.Jelaskan3"
                                        />
                                    </VControl>
                                    </div>
                                    <div class="column is-7">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input.TBJelaskan3"  />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-height"><h1>4. Memerlukan pertolongan untuk melanjutkan terapi dan
                                perawatan terus menerus</h1></td>
                            <td class="td-height">
                                <div class="columns is-multiline">
                                    <div class="column is-4" style="margin-top: 10px;margin-left: 10px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Jelaskan4"
                                            label="Jelaskan"
                                            v-model="input.Jelaskan4"
                                        />
                                    </VControl>
                                    </div>
                                    <div class="column is-7">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input.TBJelaskan4"  />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-height"><h1>5. Memerlukan bantuan melakukan kegiatan sehari-hari</h1></td>
                            <td class="td-height">
                                <div class="columns is-multiline">
                                    <div class="column is-4" style="margin-top: 10px;margin-left: 10px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Jelaskan5"
                                            label="Jelaskan"
                                            v-model="input.Jelaskan5"
                                        />
                                    </VControl>
                                    </div>
                                    <div class="column is-7">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input.TBJelaskan5"  />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-height" colspan="2">
                                <div class="columns is-multiline">
                                    <div class="column is-5" style="margin-top: 10px;">
                                        <h1>Dilanjutkan Perencanaan pemulangan dibawah ini :</h1>
                                    </div>
                                    <div class="column is-6">
                                        <VField>
                                            <VTextarea rows="1" v-model="input.DilanjutkanPerencanaan"></VTextarea>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table border="1" width="100%">
                        <tr>
                            <td width="10%"><h1>1.</h1></td>
                            <td colspan="2">
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <h1>Bantuan diperlukan dalam hal *:</h1>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="PemberianObat"
                                                label="Pemberian Obat"
                                                v-model="input.PemberianObat"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="ToiletingBABBAK"
                                                label="Toileting (BAB, BAK)"
                                                v-model="input.ToiletingBABBAK"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="MenyiapkanMakanan"
                                                label="Menyiapkan Makanan"
                                                v-model="input.MenyiapkanMakanan"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="TenagaKhusus"
                                                label="Tenaga Khusus"
                                                v-model="input.TenagaKhusus"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="PerawatanDiri"
                                                label="Perawatan Diri"
                                                v-model="input.PerawatanDiri"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="PemantauanDiet"
                                                label="Pemantauan Diet"
                                                v-model="input.PemantauanDiet"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="Berpakaian"
                                                label="Berpakaian"
                                                v-model="input.Berpakaian"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="Transportasi"
                                                label="Transportasi"
                                                v-model="input.Transportasi"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-2" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="Lainlain"
                                                label="Lain-lain"
                                                v-model="input.Lainlain"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;margin-bottom: 5px;">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input.TBLainlain"  />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><h1>2.</h1></td>
                            <td>
                                <h1>Apakah pasien tinggal sendiri setelah keluar dari rumah sakit ?</h1>
                            </td>
                            <td>
                                <div class="column is-12" style="margin-left: 5px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Tidak"
                                            label="Tidak"
                                            v-model="input.membantukeperluanTidak"
                                        />
                                    </VControl>
                                </div>
                                 <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="Ya"
                                                    label="Ya, jelaskan"
                                                    v-model="input.membantukeperluanYa"
                                                />
                                            </VControl>
                                        </div>
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="input.membantukeperluanJelaskan"  />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><h1>3.</h1></td>
                            <td>
                                <h1>Apakah pasien tinggal sendiri setelah keluar dari rumah sakit ?</h1>
                            </td>
                            <td>
                                <div class="column is-12" style="margin-left: 5px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Tidak"
                                            label="Tidak"
                                            v-model="input.tinggalsendiriTidak"
                                        />
                                    </VControl>
                                </div>
                                 <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="Ya"
                                                    label="Ya, jelaskan"
                                                    v-model="input.tinggalsendiriYa"
                                                />
                                            </VControl>
                                        </div>
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="input.tinggalsendiriJelaskan"  />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><h1>4.</h1></td>
                            <td>
                                <h1>Apakah pasien menggunakan peralatan medis di
                                rumah setelah keluar rumah sakit (cateter, NGT,
                                double lumen, oksigen) ?</h1>
                            </td>
                            <td>
                                <div class="column is-12" style="margin-left: 5px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Tidak"
                                            label="Tidak"
                                            v-model="input.menggunakanperalatanTidak"
                                        />
                                    </VControl>
                                </div>
                                 <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="Ya"
                                                    label="Ya, jelaskan"
                                                    v-model="input.menggunakanperalatanYa"
                                                />
                                            </VControl>
                                        </div>
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="input.menggunakanperalatanJelaskan"  />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><h1>5.</h1></td>
                            <td>
                                <h1>Apakah pasien memerlukan alat bantu setelah keluar
                                    dari rumah sakit (tongkat, kursi roda, walker, dll) ?</h1>
                            </td>
                            <td>
                                <div class="column is-12" style="margin-left: 5px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Tidak"
                                            label="Tidak"
                                            v-model="input.memerlukanalatbantuTidak"
                                        />
                                    </VControl>
                                </div>
                                 <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="Ya"
                                                    label="Ya, jelaskan"
                                                    v-model="input.memerlukanalatbantuYa"
                                                />
                                            </VControl>
                                        </div>
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="input.memerlukanalatbantuJelaskan"  />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><h1>6.</h1></td>
                            <td>
                                <h1>Apakah memerlukan bantuan / perawatan khusus di
                                rumah setelah keluar rumah sakit (homecare, home
                                visit) ?</h1>
                            </td>
                            <td>
                                <div class="column is-12" style="margin-left: 5px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Tidak"
                                            label="Tidak"
                                            v-model="input.perawatankhususTidak"
                                        />
                                    </VControl>
                                </div>
                                 <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="Ya"
                                                    label="Ya, jelaskan"
                                                    v-model="input.perawatankhususYa"
                                                />
                                            </VControl>
                                        </div>
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="input.perawatankhususJelaskan"  />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><h1>7.</h1></td>
                            <td>
                                <h1>Apakah pasien bermasalah dalam memenuhi
                                kebutuhan pribadinya setelah keluar dari rumah sakit
                                (makan, minum, toileting, dll) ?</h1>
                            </td>
                            <td>
                                <div class="column is-12" style="margin-left: 5px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Tidak"
                                            label="Tidak"
                                            v-model="input.pasienbermasalahTidak"
                                        />
                                    </VControl>
                                </div>
                                 <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="Ya"
                                                    label="Ya, jelaskan"
                                                    v-model="input.pasienbermasalahYa"
                                                />
                                            </VControl>
                                        </div>
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="input.pasienbermasalahJelaskan"  />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><h1>8.</h1></td>
                            <td>
                                <h1>Apakah pasien memiliki nyeri kronis dan kelelahan
                                    setelah keluar dari rumah sakit ?</h1>
                            </td>
                            <td>
                                <div class="column is-12" style="margin-left: 5px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Tidak"
                                            label="Tidak"
                                            v-model="input.nyerikronisTidak"
                                        />
                                    </VControl>
                                </div>
                                 <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="Ya"
                                                    label="Ya, jelaskan"
                                                    v-model="input.nyerikronisYa"
                                                />
                                            </VControl>
                                        </div>
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="input.nyerikronisJelaskan"  />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><h1>9.</h1></td>
                            <td>
                                <h1>Apakah pasien dan keluarga memerlukan edukasi
                                kesehatan setelah keluar dari rumah sakit (obat-
                                obatan, nyeri, diet, mencari pertolongan, follow up, dll)?</h1>
                            </td>
                            <td>
                                <div class="column is-12" style="margin-left: 5px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Tidak"
                                            label="Tidak"
                                            v-model="input.memerlukanedukasiTidak"
                                        />
                                    </VControl>
                                </div>
                                 <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="Ya"
                                                    label="Ya, jelaskan"
                                                    v-model="input.memerlukanedukasiYa"
                                                />
                                            </VControl>
                                        </div>
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="input.memerlukanedukasiJelaskan"  />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><h1>10.</h1></td>
                            <td>
                                <h1>Apakah pasien dan keluarga memerlukan
                                keterampilan khusus setelah keluar dari rumah sakit
                                (perawatan luka, injeksi, perawatan bayi, dll) ?</h1>
                            </td>
                            <td>
                                <div class="column is-12" style="margin-left: 5px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="Tidak"
                                            label="Tidak"
                                            v-model="input.memerlukanketerampilankhususTidak"
                                        />
                                    </VControl>
                                </div>
                                 <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VControl raw subcontrol>
                                                <VCheckbox
                                                    class="p-0"
                                                    color="primary"
                                                    square
                                                    :true-value="Ya"
                                                    label="Ya, jelaskan"
                                                    v-model="input.memerlukanketerampilankhususYa"
                                                />
                                            </VControl>
                                        </div>
                                        <div class="column is-6" style="margin-left: 5px;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="input.memerlukanketerampilankhususJelaskan"  />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-height" colspan="3">
                                <h1>Bila “Ya” lanjutkan dengan pemberian edukasi pada Formulir Catatan Informasi dan Edukasi Terintegrasi</h1>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-height" colspan="3">
                                <h1>Catatan tambahan apabila ada perubahan Perencanaan Pemulangan Pasien / Discharge Planning oleh PPA</h1>
                            </td>
                        </tr>
                    </table>
                    <table border="1" width="100%">
                        <tr style="text-align: center;">
                            <td>
                                <h1>Tgl, Jam,Profesi</h1>
                            </td>
                            <td>
                                <h1>Catatan</h1>
                            </td>
                            <td>
                                <h1>Paraf / Nama Jelas</h1>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="column is-12">
                                    <VField>
                                        <VDatePicker v-model="input.TglJamProfesi" mode="dateTime" style="width: 100%"
                                            trim-weeks :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal/Jam MRS"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" v-model="input.Profesi"  />
                                        </VControl>
                                    </VField>
                                </div>
                            </td>
                            <td>
                                <div class="column is-12">
                                    <VField>
                                        <VTextarea rows="2" v-model="input.Catatan"></VTextarea>
                                    </VField>
                                </div>
                            </td>
                            <td>
                                <div class="column is-12">
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="input.dokter" :suggestions="d_dokter"
                                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="" />
                                        </VControl>
                                    </VField>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-height" colspan="3">
                                <h1>Catatan Pemulangan</h1>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <h1>Sebab dipulangkan :</h1>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
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
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="MeneruskanObatJalan"
                                                label="Meneruskan Obat Jalan"
                                                v-model="input.MeneruskanObatJalan"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="PulangAtasPermintaanSendiri"
                                                label="Pulang Atas Permintaan Sendiri"
                                                v-model="input.PulangAtasPermintaanSendiri"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="MelarikanDiri"
                                                label="Melarikan Diri"
                                                v-model="input.MelarikanDiri"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-2" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="DirujukkeRS"
                                                label="Dirujuk ke RS"
                                                v-model="input.DirujukkeRS"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-4" style="margin-bottom: 5px;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="input.TBDirujukkeRS"  />
                                                </VControl>
                                            </VField>
                                        </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-height" colspan="3">
                                <div class="columns is-multiline">
                                    <div class="column is-4" style="margin-top: 10px;">
                                        <h1>Perlengkapan yang dibawa oleh pasien :</h1>
                                    </div>
                                    <div class="column is-7">
                                        <VField>
                                            <VTextarea rows="1" v-model="input.Perlengkapanyangdibawa"></VTextarea>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="10%" style="text-align: center;">1.</td>
                            <td colspan="2">
                                <div class="columns is-multiline">
                                    <div class="column is-3" style="margin-top: 10px;">
                                        <h1>Obat :</h1>
                                    </div>
                                    <div class="column is-3" style="margin-top: 10px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="'Ya'"
                                                label="Ya"
                                                v-model="input.Obat"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-top: 10px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="'Tidak'"
                                                label="Tidak"
                                                v-model="input.Obat"
                                            />
                                        </VControl>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="10%" style="text-align: center;">2.</td>
                            <td colspan="3">
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <h1>Hasil Pemeriksaan :</h1>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="HasilLab"
                                                label="Hasil Lab"
                                                v-model="input.HasilLab"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="EKG"
                                                label="EKG"
                                                v-model="input.EKG"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="FotoRontgenUSG"
                                                label="Foto Rontgen / USG"
                                                v-model="input.FotoRontgenUSG"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="Lainlain"
                                                label="Lain-lain"
                                                v-model="input.HasilLainlain"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-4" style="margin-bottom: 5px;margin-left: -50px;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="input.TBHasilpemeriksaanLainlain"  />
                                                </VControl>
                                            </VField>
                                        </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="10%" style="text-align: center;">3.</td>
                            <td colspan="3">
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <h1>Surat-surat :</h1>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="SuratKeterangan"
                                                label="Surat Keterangan"
                                                v-model="input.SuratKeterangan"
                                            />
                                        </VControl>
                                    </div>
                                    <!-- Checkbox Surat Kontrol -->
                                    <div class="column is-2" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="'SuratKontrol'"
                                                label="Surat Kontrol"
                                                v-model="input.SuratKontrol"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="columns is-multiline">
                                    <div
                                        class="column is-12"

                                        style="margin-bottom: 5px;"
                                    >
                                        <VField>
                                            <VControl>
                                                <VInput
                                                    type="text"
                                                    v-model="input.TempatSuratkontrol"
                                                    placeholder="Tempat Surat Kontrol"
                                                />
                                            </VControl>
                                        </VField>
                                    </div>

                                </div>
                                <div class="columns is-multiline">
                                    <!-- Input Tanggal Surat Kontrol -->
                                    <div
                                        class="column is-12"

                                        style="margin-bottom: 5px;"
                                    >
                                        <VField>
                                            <VDatePicker
                                                v-model="input.tanggalSuratKontrol"
                                                mode="dateTime"
                                                trim-weeks
                                                :max-date="new Date()"
                                                style="width: 100%;"
                                            >
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField>
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput
                                                                :value="inputValue"
                                                                v-on="inputEvents"
                                                                placeholder="Tanggal Surat Kontrol"
                                                            />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                    </div>
                                </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="SuratRujukan"
                                                label="Surat Rujukan"
                                                v-model="input.SuratRujukan"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="Lainlain"
                                                label="Lain-lain"
                                                v-model="input.SuratLainlain"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-4" style="margin-bottom: 5px;margin-left: -40px;">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input.TBSuratLainlain"  />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="10%" style="text-align: center;">4.</td>
                            <td colspan="3">
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <h1>Alat Bantu dan Alat Medis :</h1>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="Tidakada"
                                                label="Tidak ada"
                                                v-model="input.AlatBantuTidakada"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="DowerCateter"
                                                label="Dower Cateter"
                                                v-model="input.AlatBantuDowerCateter"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="KursiRoda"
                                                label="Kursi Roda"
                                                v-model="input.AlatBantuKursiRoda"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="Tongkat"
                                                label="Tongkat"
                                                v-model="input.AlatBantuTongkat"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="Walker"
                                                label="Walker"
                                                v-model="input.AlatBantuWalker"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="HearingAids"
                                                label="Hearing Aids"
                                                v-model="input.AlatBantuHearingAids"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-3" style="margin-left: 5px;">
                                        <VControl raw subcontrol>
                                            <VCheckbox
                                                class="p-0"
                                                color="primary"
                                                square
                                                :true-value="Lainlain"
                                                label="Lain-lain"
                                                v-model="input.AlatBantuLainlain"
                                            />
                                        </VControl>
                                    </div>
                                    <div class="column is-4" style="margin-bottom: 5px;margin-left: -80px;">
                                        <VField>
                                            <VControl>
                                                <VInput type="text" v-model="input.TBAlatBantuLainlain"  />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" border="1">
                        <tr>
                            <td width="50%">
                                <div class="column is-12" style="text-align:center;">
                                    <span style="font-weight: bold;">Discharge Planner</span> <br>
                                    <TandaTangan :elemenID="'TTDPetugas'" :width="'150'" :height="'150'" class="dek" style="margin-top: 2rem;"/>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input.Petugas" :suggestions="d_Petugas"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        class="mt-2" />
                                    </VControl>
                                </div>
                            </td>
                            <td width="50%">
                                <div class="column is-12" style="text-align:center;">
                                    <span style="font-weight: bold;">Pasien / Keluarga</span> <br>
                                    <TandaTangan :elemenID="'TTDPasien'" :width="'150'" :height="'150'" class="dek" style="margin-top: 2rem;"/>
                                    <VField style="margin-top: 10px;">
                                        <VControl>
                                            <VTextarea v-model="input.PasienKeluarga" placeholder="" rows="1">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </VCard>
        </div>

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
import { useUserSession } from '/@src/stores/userSession'
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import * as EMR from '../page-emr-plugins/discharge-planning'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let keadaanMasuk = ref(EMR.keadaanMasuk())
let keadaanKeluar = ref(EMR.keadaanKeluar())
let edukasiKeadaanKeluar = ref(EMR.edukasiKeadaanKeluar())

const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
    }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_diagnosa: any = ref([])
const d_dokter: any = ref([])
const dataTTD: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const input: any = ref({
    details: [{
        no: 1,
        tgl: new Date(),
    }]
})
const COLLECTION: any = ref('dischargePlanning') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
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
                dataTTD.value = response[0]
                console.log(dataTTD.value)
                H.tandaTangan().set('TTDPetugas', dataTTD.value.TTDPetugas)
                H.tandaTangan().set('TTDPasien', dataTTD.value.TTDPasien)
            }
        })
}
// const fetchDiagnosa = async (filter: any) => {
//     const response = await useApi().get(
//         `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`)
//     d_diagnosa.value = response
// }

const fetchDiagnosa = async (filter: any) => {
    let nama = filter.query ? `?name=${filter.query}` : ''
    const response = await useApi().get(`/emr/get-data-diagnosa${nama}`)
    d_diagnosa.value = response
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['TTDPetugas'] = H.tandaTangan().get('TTDPetugas')
    object['TTDPasien'] = H.tandaTangan().get('TTDPasien')
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

const kembaliKeun = () => {
    window.history.back()
}
const dokterDPJP = async () => {
    await useApi().get(`emr/get-dokter-dpjp?nocmfk=${ID_PASIEN}`).then((response) => {
        input.value.dokter = response.dokter
    })
}
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_dokter.value = response
}

const d_Petugas = ref([]);

const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiPerawat&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const fetchPegawai = async (filter: any) => {
    // let data = filter.query ? filter.query : filter
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
}

const getDataExist = async () => {
    await useApi().get(
        `emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    ).then((response) => {

        input.value.beratBadan = response.beratBadan
        input.value.tinggiBadan = response.tinggiBadan
        input.value.IMT = response.IMT
        input.value.lingkarPerut = response.lingkarPerut
        input.value.tekananDarah = response.tekananDarah
        input.value.pernapasan = response.pernapasan
        input.value.suhu = response.suhu
        input.value.nadi = response.nadi
    })
}
getDataExist()
const addNewItem = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
    });
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}

const print = async () => {
    H.printBlade(`emr/cetak/${COLLECTION.value}?emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

setView()
loadRiwayat()
dokterDPJP()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';


.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    border-color: var(--fade-grey-dark-3);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: center;
    vertical-align: top
}
.td-height {
    width: 50%;
    height: 60px;
    vertical-align: middle;
}
</style>
