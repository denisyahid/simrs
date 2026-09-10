<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Perencanaan Pulang Rawat Inap</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="true">
                            </ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                        isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                    </VButton>
                    <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                        isLoading="false" @click="pilihTemplate(index)"> Pilih Riwayat
                    </VButton>
                </div>

                <hr>

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

                <hr>

                <Fieldset :toggleable="true" legend="Data MRS" class="mt-3 mb-3">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VField label="Ruangan Asal">
                                <VControl icon="feather:map-pin">
                                    <VInput type="text" placeholder="" autocomplete="off"
                                        v-model="item.registrasi.namaruangan" disabled />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Diagnosa Medis : ">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.diagnosamedis"
                                        placeholder="Diagnosa Medis..." />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Tanggal / Jam MRS">
                                <VDatePicker v-model="input.tanggalmrs" mode="datetime" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Alasan MRS : ">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.alasanmrs"
                                        placeholder="Alasan MRS..." />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <label>Tanggal / Jam Dilakukan Perencanaan Pemulangan Pasien</label>
                                <VDatePicker v-model="input.tanggalpulang" mode="datetime" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Estimasi Tanggal">
                                <VDatePicker v-model="input.tanggalestimasi" mode="datetime" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                    </div>
                </Fieldset>

                <hr>

                <Fieldset :toggleable="true" legend="Kriteria Perencanaan Pemulangan Pasien yang dipenuhi*:" class="mt-3 mb-3">
                    <div class="column is-12" style="overflow: auto;">
                        <table class="tg2 table is-striped  ">
                            <tbody>
                                <tr>
                                    <td width="60%">
                                        <VField style="padding: 0px 10px;">
                                            <span>1. Usia lanjut (> 60 tahun) dengan gangguan daya ingat</span>
                                        </VField>
                                    </td>
                                    <td>
                                        <div class="columns" style="padding: 5px">
                                            <div class="column is-1 my-auto">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="true"
                                                        v-model="input.isusialanjut" />
                                                </VControl>
                                            </div>
                                            <div class="column is-11">
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Jelaskan..."
                                                        v-model="input.usialanjut" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="60%">
                                        <VField style="padding: 0px 10px;">
                                            <span>2. Bayi BBLR</span>
                                        </VField>
                                    </td>
                                    <td>
                                        <div class="columns" style="padding: 5px">
                                            <div class="column is-1 my-auto">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="true"
                                                        v-model="input.isbayiBBLR" />
                                                </VControl>
                                            </div>
                                            <div class="column is-11">
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Jelaskan..."
                                                        v-model="input.bayiBBLR" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="60%">
                                        <VField style="padding: 0px 10px;">
                                            <span>3. Keterbatasan / gangguan mobilitas</span>
                                        </VField>
                                    </td>
                                    <td>
                                        <div class="columns" style="padding: 5px">
                                            <div class="column is-1 my-auto">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="true"
                                                        v-model="input.isketerbatasan" />
                                                </VControl>
                                            </div>
                                            <div class="column is-11">
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Jelaskan..."
                                                        v-model="input.Keterbatasan" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="60%">
                                        <VField style="padding: 0px 10px;">
                                            <span>4. Memerlukan pertolongan untuk melanjutkan terapi dan
                                                perawatan terus menerus</span>
                                        </VField>
                                    </td>
                                    <td>
                                        <div class="columns" style="padding: 5px">
                                            <div class="column is-1 my-auto">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="true"
                                                        v-model="input.isMemerlukanpertolongan" />
                                                </VControl>
                                            </div>
                                            <div class="column is-11">
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Jelaskan..."
                                                        v-model="input.Memerlukanpertolongan" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="60%">
                                        <VField style="padding: 0px 10px;">
                                            <span>5. Memerlukan bantuan melakukan kegiatan sehari-hari</span>
                                        </VField>
                                    </td>
                                    <td>
                                        <div class="columns" style="padding: 5px">
                                            <div class="column is-1 my-auto">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="true"
                                                        v-model="input.isbantuan" />
                                                </VControl>
                                            </div>
                                            <div class="column is-11">
                                                <VControl>
                                                    <VInput type="text" class="input" placeholder="Jelaskan..."
                                                        v-model="input.bantuan" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </Fieldset>

                <hr>

                <Fieldset :toggleable="true" legend="Perencanaan Pemulangan" class="mt-3 mb-3">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <span>
                                1. Bantuan diperlukan dalam hal :
                            </span>
                            <div class="columns" style="padding: 10px;">
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Pemberian Obat"
                                            v-model="input.pemberianobat" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Toileting (BAB, BAK)"
                                            v-model="input.toileting" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Menyiapkan Makanan"
                                            v-model="input.menyiapkanmakanan" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Tenaga Khusus"
                                            v-model="input.tenagakhusus" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns" style="padding: 10px">
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Perawatan Diri"
                                            v-model="input.perawatandiri" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Pemantauan Diet"
                                            v-model="input.pantaudiet" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Berpakaian"
                                            v-model="input.berpakaian" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Transportasi"
                                            v-model="input.transportasi" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <span>
                                2. Adakah yang akan membantu keperluan tersebut diatas ?
                            </span>
                            <div class="columns" style="padding: 10px;">
                                <div class="column is-4 my-auto">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.bantukeperluan" />
                                    </VControl>
                                </div>
                                <div class="column is-8">
                                    <div class="columns" style="padding: 5px">
                                        <div class="column is-2 my-auto">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                    v-model="input.bantukeperluan" label="Ya" />
                                            </VControl>
                                        </div>
                                        <div class="column is-10">
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Jelaskan..."
                                                    v-model="input.bantukeperluandetail" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <span>
                                3. Apakah pasien tinggal sendiri setelah keluar dari rumah sakit ?
                            </span>
                            <div class="columns" style="padding: 10px;">
                                <div class="column is-4 my-auto">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.tinggalsendiriKRS" />
                                    </VControl>
                                </div>
                                <div class="column is-8">
                                    <div class="columns" style="padding: 5px">
                                        <div class="column is-2 my-auto">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                    v-model="input.tinggalsendiriKRS" label="Ya" />
                                            </VControl>
                                        </div>
                                        <div class="column is-10">
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Jelaskan..."
                                                    v-model="input.tinggalsendiriKRSdetail" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <span>
                                4. Apakah pasien menggunakan peralatan medis di rumah setelah keluar rumah sakit (cateter, NGT, double lumen, oksigen) ?
                            </span>
                            <div class="columns" style="padding: 10px;">
                                <div class="column is-4 my-auto">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.peralatanKRS" />
                                    </VControl>
                                </div>
                                <div class="column is-8">
                                    <div class="columns" style="padding: 5px">
                                        <div class="column is-2 my-auto">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                    v-model="input.peralatanKRS" label="Ya" />
                                            </VControl>
                                        </div>
                                        <div class="column is-10">
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Jelaskan..."
                                                    v-model="input.peralatanKRSdetail" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <span>
                                5. Apakah pasien memerlukan alat bantu setelah keluar dari rumah sakit (tongkat, kursi roda, walker, dll) ?
                            </span>
                            <div class="columns" style="padding: 10px;">
                                <div class="column is-4 my-auto">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.perlualatKRS" />
                                    </VControl>
                                </div>
                                <div class="column is-8">
                                    <div class="columns" style="padding: 5px">
                                        <div class="column is-2 my-auto">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                    v-model="input.perlualatKRS" label="Ya" />
                                            </VControl>
                                        </div>
                                        <div class="column is-10">
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Jelaskan..."
                                                    v-model="input.perlualatKRSdetail" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <span>
                                6. Apakah memerlukan bantuan / perawatan khusus di rumah setelah keluar rumah sakit (homecare, home visit) ?
                            </span>
                            <div class="columns" style="padding: 10px;">
                                <div class="column is-4 my-auto">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.bantuanperawatKRS" />
                                    </VControl>
                                </div>
                                <div class="column is-8">
                                    <div class="columns" style="padding: 5px">
                                        <div class="column is-2 my-auto">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                    v-model="input.bantuanperawatKRS" label="Ya" />
                                            </VControl>
                                        </div>
                                        <div class="column is-10">
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Jelaskan..."
                                                    v-model="input.bantuanperawatKRSdetail" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <span>
                                7. Apakah pasien bermasalah dalam memenuhi kebutuhan pribadinya setelah keluar dari rumah sakit (makan, minum, toileting, dll) ?
                            </span>
                            <div class="columns" style="padding: 10px;">
                                <div class="column is-4 my-auto">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.bermasalahKRS" />
                                    </VControl>
                                </div>
                                <div class="column is-8">
                                    <div class="columns" style="padding: 5px">
                                        <div class="column is-2 my-auto">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                    v-model="input.bermasalahKRS" label="Ya" />
                                            </VControl>
                                        </div>
                                        <div class="column is-10">
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Jelaskan..."
                                                    v-model="input.bermasalahKRSdetail" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <span>
                                8. Apakah pasien memiliki nyeri kronis dan kelelahan setelah keluar dari rumah sakit ?
                            </span>
                            <div class="columns" style="padding: 10px;">
                                <div class="column is-4 my-auto">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.nyeriKRS" />
                                    </VControl>
                                </div>
                                <div class="column is-8">
                                    <div class="columns" style="padding: 5px">
                                        <div class="column is-2 my-auto">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                    v-model="input.nyeriKRS" label="Ya" />
                                            </VControl>
                                        </div>
                                        <div class="column is-10">
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Jelaskan..."
                                                    v-model="input.nyeriKRSdetail" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <span>
                                9. Apakah pasien dan keluarga memerlukan edukasi kesehatan setelah keluar dari rumah sakit (obat-obatan, nyeri, diet, mencari pertolongan, follow up, dll) ?
                            </span>
                            <div class="columns" style="padding: 10px;">
                                <div class="column is-4 my-auto">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.edukasiKRS" />
                                    </VControl>
                                </div>
                                <div class="column is-8">
                                    <div class="columns" style="padding: 5px">
                                        <div class="column is-2 my-auto">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                    v-model="input.edukasiKRS" label="Ya" />
                                            </VControl>
                                        </div>
                                        <div class="column is-10">
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Jelaskan..."
                                                    v-model="input.edukasiKRSdetail" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <span>
                                10. Apakah pasien dan keluarga memerlukan keterampilan khusus setelah keluar dari rumah sakit (perawatan luka, injeksi, perawatan bayi, dll) ?
                            </span>
                            <div class="columns" style="padding: 10px;">
                                <div class="column is-4 my-auto">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                            v-model="input.keterampilanKRS" />
                                    </VControl>
                                </div>
                                <div class="column is-8">
                                    <div class="columns" style="padding: 5px">
                                        <div class="column is-2 my-auto">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                    v-model="input.keterampilanKRS" label="Ya" />
                                            </VControl>
                                        </div>
                                        <div class="column is-10">
                                            <VControl>
                                                <VInput type="text" class="input" placeholder="Jelaskan..."
                                                    v-model="input.keterampilanKRSdetail" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>

                <hr>

                <Fieldset :toggleable="false" legend="ASESMEN KEBUTUHAN INFORMASI DAN EDUKASI" class="mt-3 mb-3">
                    <div class="column is-12">
                        <span>Bila "Ya" lanjutkan dengan pemberian edukasi pada Formulir Catatan Informasi dan Edukasi Terintegrasi</span>
                    </div>
                </Fieldset>

                <hr>

                <Fieldset :toggleable="true" legend="Catatan tambahan apabila ada perubahan Perencanaan Pemulangan Pasien / Discharge Planning oleh PPA" class="mt-3 mb-3">
                    <div class="column is-12">
                        <table class="table" style="width: auto;">
                            <thead>
                                <tr>
                                    <th class="th-popri" width="10%">No</th>
                                    <th class="th-popri" width="20%">Tanggal / Jam</th>
                                    <th class="th-popri" width="40%">Nama / Paraf</th>
                                    <th class="th-popri" width="10%">#</th>
                                </tr>
                            </thead>
                            <tbody v-for="(item, index) in input.details" :key="index">
                                <tr>
                                <td class="td-popri">
                                    <span>{{index + 1}}</span>
                                </td>

                                <td class="td-popri">
                                    <VDatePicker v-model="item.tgljamrencana" mode="date" style="width: 100%; padding-top:10px" trim-weeks>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField style="margin-bottom: 0.70rem;">
                                                <VControl class="prime-auto" icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </td>
                                <td class="td-popri">
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="item.pegawai" :suggestions="d_Petugas" @complete="fetchPegawai($event)"
                                            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                            :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai..." />
                                        </VControl>
                                    </VField>
                                </td>
                                <!-- <td class="td-popri">
                                    <TandaTangan :elemenID="'TTDBidan'" :width="'150'" :height="'150'" class="dek" />
                                </td> -->
                                <td class="td-popri">
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
                    <div class="column is-12">
                        <VField label="Catatan Pemulangan">
                            <VControl>
                                <VTextarea
                                    v-model="input.catatanpulang"
                                    rows="4"
                                />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-12">
                        <span>
                            Sebab dipulangkan :
                        </span>
                        <div class="columns" style="padding: 10px;">
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="sembuh" label="Sembuh"
                                        v-model="input.sebabDipulangkan" />
                                </VControl>
                            </div>
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="meneruskan obat jalan" label="Meneruskan Obat Jalan"
                                        v-model="input.sebabDipulangkan" />
                                </VControl>
                            </div>
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="pulang atas permintaan sendiri" label="Pulang Atas Permintaan Sendiri"
                                        v-model="input.sebabDipulangkan" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns" style="padding: 10px">
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="melarikan diri" label="Melarikan Diri"
                                        v-model="input.sebabDipulangkan" />
                                </VControl>
                            </div>
                            <div class="column is-8">
                                <div class="columns" style="padding: 5px">
                                    <div class="column is-3 my-auto">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="dirujuk"
                                                v-model="input.sebabDipulangkan" label="Dirujuk Ke RS..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-9">
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Jelaskan..."
                                                v-model="input.sebabDipulangkandetail" />
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>

                <hr>

                <Fieldset :toggleable="true" legend="Perlengkapan yang dibawa oleh pasien" class="mt-3 mb-3">
                    <div class="column is-12">
                        <span>
                            1. Obat :
                        </span>
                        <div class="columns" style="padding: 10px;">
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Ya" label="Ya"
                                        v-model="input.perlengkapanObat" />
                                </VControl>
                            </div>
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak" label="Tidak"
                                        v-model="input.perlengkapanObat" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <span>
                            2. Hasil Pemeriksaan :
                        </span>
                        <div class="columns" style="padding: 10px;">
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Hasil Lab" label="Hasil Lab"
                                        v-model="input.hasilperiksa" />
                                </VControl>
                            </div>
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="EKG" label="EKG"
                                        v-model="input.hasilperiksa" />
                                </VControl>
                            </div>
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Foto Rontgen / USG" label="Foto Rontgen / USG"
                                        v-model="input.hasilperiksa" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns" style="padding: 10px;">
                            <div class="column is-12">
                                <div class="columns">
                                    <div class="column is-2 my-auto">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                                v-model="input.hasilperiksa" label="Lain-lain..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-10">
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Jelaskan..."
                                                v-model="input.hasilperiksadetail" />
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <span>
                            3. Surat Surat :
                        </span>
                        <div class="columns" style="padding: 10px;">
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Surat Keterangan" label="Surat Keterangan"
                                        v-model="input.suratsurat" />
                                </VControl>
                            </div>
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Surat Rujukan" label="Surat Rujukan"
                                        v-model="input.suratsurat" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns" style="padding: 10px;">
                            <div class="column is-12">
                                <div class="columns">
                                    <div class="column is-2 my-auto">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Surat Kontrol"
                                                v-model="input.suratsurat" label="Surat Kontrol" />
                                        </VControl>
                                    </div>
                                    <div class="column is-5">
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Tempat..."
                                                v-model="input.suratsurattempat" />
                                        </VControl>
                                    </div>
                                    <div class="column is-5">
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Tanggal..."
                                                v-model="input.suratsurattanggal" />
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns">
                                <div class="column is-2 my-auto">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                            v-model="input.suratsurat" label="Lain-lain..." />
                                    </VControl>
                                </div>
                                <div class="column is-10">
                                    <VControl>
                                        <VInput type="text" class="input" placeholder="Jelaskan..."
                                            v-model="input.suratsuratdetail" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <span>
                            4. Alat Bantu dan Alat Medis :
                        </span>
                        <div class="columns" style="padding: 10px;">
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak ada" label="Tidak ada"
                                        v-model="input.alatbantumedis" />
                                </VControl>
                            </div>
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Dower Cateter" label="Dower Cateter"
                                        v-model="input.alatbantumedis" />
                                </VControl>
                            </div>
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Kursi Roda" label="Kursi Roda"
                                        v-model="input.alatbantumedis" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns" style="padding: 10px;">
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Tongkat" label="Tongkat"
                                        v-model="input.alatbantumedis" />
                                </VControl>
                            </div>
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Walker" label="Walker"
                                        v-model="input.alatbantumedis" />
                                </VControl>
                            </div>
                            <div class="column is-4 my-auto">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square true-value="Hearing Aids" label="Hearing Aids"
                                        v-model="input.alatbantumedis" />
                                </VControl>
                            </div>
                        </div>
                        <div class="columns" style="padding: 10px;">
                            <div class="column is-12">
                                <div class="columns">
                                    <div class="column is-2 my-auto">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                                v-model="input.alatbantumedis" label="Lain-lain..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-10">
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Jelaskan..."
                                                v-model="input.alatbantumedisdetail" />
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Fieldset>

                <hr>

                <div class="columns mr-5">
                    <div class="column is-7"></div>
                    <div class="column is-5 ml-auto">
                        <VField label="Garut">
                            <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                        <div class="column" style="text-align:center;">
                            <h1>Tanda Tangan</h1>
                            <TandaTangan :elemenID="'ttdPegawai'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.ttdPegawaiNama" :suggestions="d_Petugas"
                                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                </div>
                <!-- form baru -->
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
                                    <td class="tg-0lax text-center" width="15%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="15%">Tanggal Registrasi</td>
                                    <td class="tg-0lax text-center" width="15%">No Registrasi</td>
                                    <td class="tg-0lax text-center" width="15%">No EMR</td>
                                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                                    <td class="tg-0lax text-center" width="15%">Section</td>
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
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

    <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
        @close="showModalTemplateFix = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Template</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="5%">No</td>
                                    <td class="tg-0lax text-center" width="15%">Tanggal Dibuat</td>
                                    <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                                    <td class="tg-0lax text-center" width="25%">Nama Template</td>
                                    <td class="tg-0lax text-center" width="15%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplateFix">
                                <tr>
                                    <td style="width:5%;text-align:center">
                                        <span class="mb-2">{{ resep.no }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.namatemplate }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
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

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';


// Judul
useHead({
    title: 'Perencanaan Pulang Rawat Inap - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])
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
const fetchPetugas = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&limit=10`).then((response) => {
        d_Petugas.value = response
    })
}
const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}
const dataTTD: any = ref([])
const route = useRoute()
const pasien: any = ref({})
const d_Petugas: any = ref([])
const loadData: any = ref(true)
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
    filter: '',
    airway: [],
    disability: []
})
const COLLECTION: any = ref("PerencanaanPulangRanap") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const alertMid = ref(false);
const d_Dokter: any = ref([])
const input: any = ref({
  details: [{
    no: 1,
  }]
})

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
  const fetchPerawat = async (filter: any) => {
    // let data = filter.query ? filter.query : filter
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
      d_Petugas.value = response
    })
  }

const loadRiwayat = async () => {
    isLoading.value = true
    let histori = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (histori.length) {
        input.value = histori[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = histori[0].emrpasienfk
        }
        dataTTD.value = histori[0]
        H.tandaTangan().set("ttdPegawai", dataTTD.value.ttdPegawai)
    }
    isLoading.value = false
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object['ttdPegawai'] = H.tandaTangan().get("ttdPegawai");
    // object['TTDDokterPemeriksa'] = H.tandaTangan().get("TTDDokterPemeriksa");
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': 'perencanaan-pulang-ranap',
        'name_form': 'Perencanaan Pulang Rawat Inap',
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    console.log(json)

    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            NOREC_EMRPASIEN.value = response.norec_emr
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
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
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

const setRoutingEMR = (form: any, norec_emr: any) => {

let query: any = {}
let params: any = {}
console.log("DATA ITEM", item);

if (NOREC_EMRPASIEN.value != '') {
  query = {
    nocmfk: pasien.value.nocmfk,
    norec_pasien_daftar: item.NOREC_PD,
    norec_pd: item.NOREC_PD,
    norec_apd: item.NOREC_APD,
    jenisobgyn: '',
    norec_emr: NOREC_EMRPASIEN.value,
  }
} else {
  query = {
    nocmfk: pasien.value.nocmfk,
    norec_pasien_daftar: item.NOREC_PD,
    norec_pd: item.NOREC_PD,
    norec_apd: item.NOREC_APD,
    jenisobgyn: '',
  }
}

console.log(query)
if (form.indexOf('index_tab') > -1) {
  params = {
    index_tabs: 1
  }
}
router.push({
  name: form,
  query: query,
  params: params
})
}

const pilihTemplate = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
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
    console.log(response)
    input.value = response //set ke inputan
    input.value.namatemplate = null
}

const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
            isLoading.value = false
            console.log(responselast)
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

const kembaliKeun = () => {
    window.history.back()
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
}

const addNewItem = () => {
  input.value.details.push({
    no: input.value.details[input.value.details.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}


onBeforeMount(async () => {
    try {
        await loadRiwayat()
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

fetchPasien();

</script>

<style lang="scss">
.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 150%;
}

.tg2 {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg2 td {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg2 th {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
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
    text-align: left;
    vertical-align: middle
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 0px;
}
.alert-nobg {
    background: none !important;
    height: 5em;
    width: 5em;

}
</style>
