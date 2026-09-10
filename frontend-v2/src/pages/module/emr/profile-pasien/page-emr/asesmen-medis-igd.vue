<template>
    <div class="form-layout is-stacked-2" style="
    width: 100%;
    max-width: none;">
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
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoading" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="form-body p-2">
        <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
        <div class="column is-12" v-else>
            <div class="column is-12">
                <Fieldset :toggleable="true" legend="A. Anamnesis (S)">
                    <div class="column is-12 pl-0 pr-0">
                        <div class="column is-12">
                            <div class="columns">
                                <div class="column" v-for="(pilihan) in anamnesa">
                                    <h1 class="mb-3 emr">{{ pilihan.title }}</h1>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input[pilihan.model]" rows="3">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pl-0 pr-0">
                        <div class="column is-8" v-for="(pilihan) in listFaktorResJantung">
                            <h1 class="emr">{{ pilihan.title }}</h1>
                            <div class="columns is-multiline pt-3">
                                <div class="column is-one-quarter" v-for="(init) in pilihan.value">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[pilihan.model]" :true-value="init" :label="init"
                                                color="primary" square />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="columns pl-3">
                            <div class="column is-3">
                                <h1 class="emr">Merokok</h1>
                                <div class="is-flex">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input.merokok" true-value="Ya" label="Ya (Berapa pack/hari)"
                                                color="danger" square />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div class="column is-5 pt-5 mt-4">
                                <VControl>
                                    <input v-model="input.keterangMerokok" type="text" class="input" />
                                </VControl>
                            </div>
                            <div class="column pt-5 mt-3">
                                <div class="is-flex">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input.merokok" true-value="Tidak" label="Tidak"
                                                color="primary" square />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="column is-8">
                            <h1 class="mb-3 emr">Riwayat Alergi</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.riwayatAlergi" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-8">
                            <h1 class="mb-3 emr">Riwayat Pengobatan</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.riwayatPengobatan" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </Fieldset>
            </div>
            <div class="column is-12">
                <Fieldset :toggleable="true" legend="B. Pemeriksaan Fisik (O)">
                    <div class="column is-10" v-for="(data) in keadaanUmum">
                        <h1 class="mb-3 emr">{{ data.title }}</h1>
                        <div class="columns">
                            <div class="column is-3" v-for="(pilihan) in data.value">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model]" :true-value="pilihan" :label="pilihan"
                                            color="primary" square />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold;"> 2. Tanda Vital</h1>
                        <div class="columns is-multiline">
                            <div class="column is-2 mt-2">
                                <VField label="Tekanan Darah"></VField>
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
                            <div class="column is-3 mt-2">
                                <VField label="Frekuensi Nadi"></VField>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadi" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/menit</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2 mt-2">
                                <VField label="Suhu"></VField>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder="Suhu" v-model="input.suhu" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>°C </VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 mt-2">
                                <VField label="Frekuensi Nafas"></VField>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder="Nafas" v-model="input.pernapasan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/menit</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2 mt-2">
                                <VField label="Skor Nyeri"></VField>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder="Skor Nyeri"
                                            v-model="input.skorNyeri" />
                                    </VControl>

                                </VField>
                            </div>


                        </div>
                    </div>

                    <div class="column is-12">
                        <h1 style="font-weight: bold;"> 3. Antropometri</h1>
                        <div class="columns is-multiline">
                            <div class="column is-3 mt-2">
                                <VField label="Berat Badan"></VField>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder="Berat Badan"
                                            v-model="input.beratBadan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>kg</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 mt-2">
                                <VField label="Tinggi badan"></VField>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder="Tinggi Badan"
                                            v-model="input.tinggiBadan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>cm</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 mt-2">
                                <VField label="Lingkar Perut"></VField>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder="Lingkar Perut"
                                            v-model="input.lingkarPerut" />
                                    </VControl>

                                </VField>
                            </div>
                            <div class="column is-3 mt-2">
                                <VField label="IMT"></VField>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" placeholder="IMT" v-model="input.IMT" />
                                    </VControl>

                                </VField>
                            </div>


                        </div>
                    </div>

                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="4. Kesadaran">
                            <div class="columns p-3">
                                <div class="column is-6 pb-0">
                                    <div class="columns">
                                        <div class="column is-1">
                                            <h1 class="mb-3 emr" style="text-align: center;">No</h1>
                                        </div>
                                        <div class="column is-one-quarter" style="text-align: center;">
                                            <h1 class="mb-3 emr">Parameter</h1>
                                        </div>
                                        <div class="column is-one-quarter" style="text-align: center;">
                                            <h1 class="mb-3 emr">Nilai</h1>
                                        </div>
                                    </div>
                                    <div class="columns pb-4" v-for="(data) in kesadaran">
                                        <div class="column is-1 pt-0" style="text-align: center;">
                                            <h1 class="mb-3 emr"> {{ data.no }}</h1>
                                        </div>
                                        <div class="column  is-one-quarter pt-0" style="text-align: center;">
                                            <h1 class="mb-3 emr">{{ data.parameter }}</h1>
                                        </div>
                                        <div class="column  is-one-quarter pt-1" style="text-align: center;">
                                            <VField v-for="(pilihan) in data.nilai">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="pb-0" :true-value="pilihan.poin"
                                                        v-model="input[pilihan.model]" :label="pilihan.poin"
                                                        color="primary" />
                                                </VControl>
                                            </VField>
                                        </div>

                                    </div>
                                    <hr>
                                </div>
                                <div class="column is-6">
                                    <VCard>
                                        <p>KETERANGAN</p>
                                        <div class="column" v-for="(data) in descRangeKesadaran">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="pb-0" :true-value="data.poin"
                                                        v-model="input[data.model]" :label="data.des" color="primary" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </VCard>
                                </div>
                            </div>
                            <div class="column is-4 pt-0" style="margin-left: auto;">
                                <VField>
                                    <h1 style="font-weight: bold;" class="mb-2">JUMLAH TOTAL</h1>
                                    <VControl>
                                        <input v-model="input.totalKesadaran" class="input" placeholder="Jumlah" disabled />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField>
                                    <VControl>
                                        <VTextarea v-model="input.keteranganKesadaran" rows="3">
                                        </VTextarea>
                                    </VControl>
                                </VField>
                            </div>
                        </Fieldset>
                    </div>

                    <div class="column is-12" v-for="(datas) in keadaanUmum2">
                        <h1 style="font-weight: bold;" class="mb-2">{{ datas.title }}</h1>
                        <div class="column is-9 pb-0" :class="detail.model.length > 3 ? 'column is-12' : ''"
                            v-for="(detail) in datas.value">
                            <h1 style="font-weight: bold;" class="mb-4" v-if="detail.subTitle">{{
                                detail.subTitle }}</h1>

                            <div class="column p-0" v-if="detail.optional.length > 1">
                                <div class="columns is-multiline is-mobile pl-3 pr-3">
                                    <div class="column is-one-quarter" v-for="(pilihan) in detail.item">
                                        <VField>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="pilihan"
                                                    :label="pilihan" color="primary" square />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                                <div class="columns is-multiline pl-3">
                                    <div class="column is-one-quarter" v-for="(desc) in detail.optional">
                                        <VField>
                                            <VControl>
                                                <input v-model="input[desc.model]" class="input" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>

                            <div class="column p-0" v-else>
                                <div class="columns is-multiline is-mobile pl-3 pr-3">
                                    <div class="column is-one-quarter" v-for="(pilihan) in detail.item">
                                        <VField>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="pilihan"
                                                    :label="pilihan" color="primary" square />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>

                                <div class="column" v-for="(desc) in detail.optional">
                                    <VField>
                                        <VControl>
                                            <input v-model="input[desc.model]" class="input" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="column is-12">
                        <h1 class="mb-3 emr">15. Leher</h1>
                        <div class="column is-8">
                            <VField>
                                <VControl icon="feather:bookmark">
                                    <VInput v-model.number="input.leher" placeholder="Tulis Angka " />
                                </VControl>
                            </VField>
                            <Slider v-model="input.leher" />
                        </div>
                    </div>

                </Fieldset>
            </div>

            <div class="column is-12">
                <Fieldset :toggleable="true" legend="C. Status Lokalis">
                    <div class="columns is-multiline pl-5">
                        <div class="column is-3">
                            <img src="/images/emr/asesmen_medis.png" style="width: 12rem !important;" />
                        </div>
                        <div class="column is-6" style="margin-top: 4rem">
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.keteranganstatusLokalis" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </Fieldset>
            </div>

            <div class="column is-12">
                <Fieldset :toggleable="true" legend="D. Pemeriksaan Penunjang">
                    <div class="column" v-for="(data) in pemeriksaanPenunjang">
                        <div class="columns is-multiline pl-5 pr-5">
                            <div class="column is-one-quarter">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.title"
                                            color="primary" square />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column">
                                <VControl>
                                    <input v-model="input[data.desc]" class="input" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                    <div class="column pl-5 pr-5 ml-2 mr-2">
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.pemeriksaanPenunjang" rows="3">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-12">
                <Fieldset :toggleable="true" legend="E. Diagnosa">
                    <div class="column is-12">
                        <VTabs selected="icd9" :tabs="[
                            { label: 'Diagnosa ICD 9', value: 'icd9' },
                            { label: 'Diagnosa ICD 10', value: 'icd10' },
                        ]">
                            <template #tab="{ activeValue }">
                                <p v-if="activeValue === 'icd9'">
                                    <DataTable :value="dataSourceICD9" class="p-datatable-sm" :loading="isLoading"
                                        :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]" selectionMode="single"
                                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                        showGridlines>

                                        <Column field="no" header="#"></Column>
                                        <Column field="noregistrasi" header="No Registrasi"></Column>
                                        <Column field="kddiagnosatindakan" header="Kode ICD 9"></Column>
                                        <Column field="namadiagnosatindakan" header="Nama ICD 9"></Column>
                                        <Column field="namaruangan" header="Rungan"></Column>
                                        <Column field="tglInput" header="Tgl Input"></Column>
                                    </DataTable>
                                </p>
                                <p v-else-if="activeValue === 'icd10'">
                                    <DataTable :value="dataSourceICD10" class="p-datatable-sm" :loading="isLoading"
                                        :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]" selectionMode="single"
                                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                        showGridlines>

                                        <Column field="no" header="#"></Column>
                                        <Column field="noregistrasi" header="No Registrasi" />
                                        <Column field="jenisdiagnosa" header="Jenis Diagnosa" />
                                        <Column field="kddiagnosa" header="Kode ICD 10" />
                                        <Column field="namadiagnosa" header="Nama ICD 10" />
                                        <Column field="namaruangan" header="Ruangan" />
                                        <Column field="tglInput" header="TGL Input" />
                                    </DataTable>
                                </p>
                            </template>
                        </VTabs>
                    </div>
                </Fieldset>
            </div>
            <div class="column is-12">
                <Fieldset class="p-fieldsets" legend="F. Tata Laksana" :toggleable="true">
                    <div class="column is-4">
                        <h1 style="font-weight: bold;" class="mb-2">Waktu Pelaksanaan</h1>
                        <VField>
                            <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
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
                    <div class="column is-12 mt-0">
                        <VField>
                            <VControl>
                                <VTextarea class="textarea" v-model="input.keteranganPelaksanaan" rows="2"
                                    placeholder="Keterangan Tata Laksana" autocomplete="off" autocapitalize="off"
                                    spellcheck="true" />
                            </VControl>
                        </VField>
                    </div>

                </Fieldset>
            </div>
            <div class="column is-12">
                <Fieldset class="p-fieldsets" legend="G. Pragnosis" :toggleable="true">
                    <div class="column -is 12">
                        <div class="columns is-multiline pt-3 pl-3">
                            <div class="column is-6" v-for="(data, i) in Pragnosis">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                            :label="data.title" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                </Fieldset>
            </div>
            <div class="column is-12">
                <Fieldset class="p-fieldsets" legend="H. Rencana Selanjutnya" :toggleable="true">
                    <div class="column is-4">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input.RencanaRawat" true-value="Rawat" label="Rawat" color="primary"
                                    square />
                            </VControl>
                        </VField>
                    </div>

                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">
                                Konsul Dokter Spesialis</h1>
                            <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                    <AutoComplete v-model="input.dokterSpesialis" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="ketik untuk mencari..." />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;"> Tanggal</h1>
                            <VField>
                                <VDatePicker v-model="input.tanggalRawat" mode="dateTime" style="width: 100%" trim-weeks
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
                        <div class="column is-1 mt-6">
                            <VField>
                                <VControl>
                                    <VCheckbox v-model="input.onCall" true-value="onCall" label="On Call" color="primary"
                                        square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1 mt-6">
                            <VField>
                                <VControl>
                                    <VCheckbox v-model="input.onSite" true-value="onSite" label="On Site" color="primary"
                                        square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <Vfield>
                                <VControl>
                                    <VTextarea class="textarea" v-model="input.keteranganRawat" rows="2"
                                        placeholder="Keterangan" autocomplete="off" autocapitalize="off"
                                        spellcheck="true" />
                                </VControl>
                            </Vfield>

                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline pt-3 pl-3">
                            <div class="column is-3" v-for="(data, i) in pilihanRencana">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                            :label="data.title" class="p-0" color="primary" square />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;"> Kontrol Poli</h1>
                            <VField>
                                <VDatePicker v-model="input.tanggalKontrolPoli" mode="dateTime" style="width: 100%"
                                    trim-weeks :max-date="new Date()">
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
                        <div class="column is-8" style="margin-top: 2.5rem;">
                            <VField>
                                <VControl>
                                    <VInput type="text" v-model="input.keteranganRencana" placeholder="Keterangan" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;"> Terapi Pulang</h1>
                            <Vfield>
                                <VControl>
                                    <VTextarea class="textarea" v-model="input.keteranganTerapiPulang" rows="2"
                                        placeholder="Keterangan Terapi Pulang" autocomplete="off" autocapitalize="off"
                                        spellcheck="true" />
                                </VControl>
                            </Vfield>
                        </div>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VControl raw subcontrol>
                                <VCheckbox v-model="input.rencanaRujuk" true-value="Rujuk" label="Rujuk" color="primary"
                                    square />
                            </VControl>
                        </VField>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;"> Rujuk</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" v-model="input.alamatRujuk" placeholder="Rujuk ke...." />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-8">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;"> Alasan Rujuk</h1>
                            <Vfield>
                                <VControl>
                                    <VTextarea class="textarea" v-model="input.keteranganTerapiPulang" rows="1"
                                        placeholder="Keterangan Terapi Pulang" autocomplete="off" autocapitalize="off"
                                        spellcheck="true" />
                                </VControl>
                            </Vfield>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <h1 style="font-weight: bold;"> Edukasi tentang diagnosis kepada
                            </h1>
                            <div class="columns is-multiline pt-3 pl-3">
                                <div class="column is-6" v-for="(data, i) in Edukasi">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                :label="data.title" class="p-0" color="primary" square />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <h1 style="font-weight: bold;"> Keadaan pasien setelah keluar dari
                                rumah sakit
                            </h1>
                            <div class="columns is-multiline pt-3 pl-3">
                                <div class="column is-6" v-for="(data, i) in keadaanPasien">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                :label="data.title" class="p-0" color="primary" square />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Tekanan Darah</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Tekanan Darah"
                                        v-model="input.tekananDarahsaatKeluar" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>mmHg</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Frekuensi Nadi</h1>

                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadiSaatKeluar" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>x/menit</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Frekuensi Nafas</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Nafas"
                                        v-model="input.frekuensiNafassaatKeluar" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>x/menit</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Suhu</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Suhu" v-model="input.suhuSaatkeluar" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>°C </VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Saturasi O2 (SpO2)</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Saturasi O2 (SpO2)"
                                        v-model="input.Sp02saatKeluar" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>%</VButton>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-6">
                <VCard class="border-card pink">
                    <div class="column is-8 Sp-0">
                        <VField>
                            <h1 class="mb-3 emr">Bandung, Tanggal dan Jam</h1>
                            <VDatePicker v-model="input.waktuAsesmen" mode="dateTime" style="width: 100%" trim-weeks
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
                    <div class="column is-8 Sp-0">
                        <h1 style="font-weight: bold; margin-bottom: 1rem;">
                            Dokter</h1>
                        <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                <AutoComplete v-model="input.dokterAsesmen" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="ketik untuk mencari..." />
                            </VControl>
                        </VField>
                    </div>

                </VCard>
            </div>

        </div>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as H from '/@src/utils/appHelper'
import Slider from 'primevue/slider';
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import * as EMR from '../page-emr-plugins/asesmen-medis-igd'


useHead({
    title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let anamnesa = ref(EMR.anamnesa())
let listFaktorResJantung = ref(EMR.listFaktorResJantung())
let keadaanUmum = ref(EMR.keadaanUmum())
let keadaanUmum2 = ref(EMR.keadaanUmum2())
let pemeriksaanFisik = ref(EMR.pemeriksaanFisik())
let kesadaran = ref(EMR.kesadaran())
let descRangeKesadaran = ref(EMR.descRangeKesadaran())
let Pragnosis = ref(EMR.Pragnosis())
let pemeriksaanPenunjang = ref(EMR.pemeriksaanPenunjang())
let Edukasi = ref(EMR.Edukasi())
let pilihanRencana = ref(EMR.pilihanRencana())
let keadaanPasien = ref(EMR.keadaanPasien())


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

const route = useRoute()
const pasien: any = ref({})
const loadData: any = ref(true)
const isLoadingPasien: any = ref(false)
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

const COLLECTION: any = ref('AsesmenMedisIGD') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_ko: any = ref('')
const input: any = ref({})
const d_Dokter: any = ref([])
const dataSourceICD9: any = ref([])
const dataSourceICD10: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)


const diagnosa = async () => {
    await useApi().get(`emr/get-diagnosa-pasien-icd9?nocmfk=${ID_PASIEN}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
        });
        dataSourceICD9.value = response
        // console.log(response)
    })
    await useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
        });
        dataSourceICD10.value = response
    })
}

const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
    }
}
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Dokter.value = response
}

const simpan = () => {

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
    // console.log(json)

    // // isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            // NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoading.value = false
        })

    // console.log(resultValue)
}

const kembaliKeun = () => {
    window.history.back()
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const setAutoFill = async () => {
    input.value.tanggal = new Date()
    input.value.waktuAsesmen = new Date()
    input.value.dokterAsesmen = {
        label: H.pegawaiLogin().namalengkap,
        value: H.pegawaiLogin().id
    }

    await useApi().get(
        "emr/auto-fill?nocmfk=" + ID_PASIEN +
        "&collection=VitalSign" +
        "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
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

diagnosa()
setAutoFill()
fetchPasien()

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

watch(() => [
    input.value.kesadaranE,
    input.value.kesadaranM,
    input.value.kesadaranV,
    input.value.totalKesadaran,
    // input.value.point2
], () => {
    let poin1 = input.value.kesadaranE ? parseInt(input.value.kesadaranE) : 0
    let poin2 = input.value.kesadaranM ? parseInt(input.value.kesadaranM) : 0
    let poin3 = input.value.kesadaranV ? parseInt(input.value.kesadaranV) : 0

    const jumlahNilai = poin1 + poin2 + poin3
    input.value.totalKesadaran = jumlahNilai
})

watch(() => input.value.totalKesadaran, (newValue) => {
    if (newValue >= 14) {
        input.value.rangeKesadaran = "15";
    } else if (newValue >= 12 && newValue <= 13) {
        input.value.rangeKesadaran = "13";
    } else if (newValue >= 10 && newValue <= 11) {
        input.value.rangeKesadaran = "11";
    } else if (newValue >= 7 && newValue <= 9) {
        input.value.rangeKesadaran = "9";
    } else if (newValue >= 4 && newValue <= 6) {
        input.value.rangeKesadaran = "6";
    } else if (newValue <= 3) {
        input.value.rangeKesadaran = "2";
    }
});
let sudahDisimpan = ref(false)
onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name + '-' + route.params.index_tabs
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
        if (!sudahDisimpan.value) {
            console.log("DISIMPAN",sudahDisimpan.value);

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


</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}

.p-fieldset-legend {
    margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
    background: none;
}

.checkbox.is-outlined {
    padding: unset !important;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

h1.emr {
    font-weight: bold;
}

table.assesment {
    border-collapse: collapse;
    width: 100%;
}


.assesment th {
    text-align: center !important;
    border-bottom: 1px solid black;
    // border: 1px solid black;
}


.assesment th,
td {
    padding: 8px;
    vertical-align: middle !important;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}
</style>
