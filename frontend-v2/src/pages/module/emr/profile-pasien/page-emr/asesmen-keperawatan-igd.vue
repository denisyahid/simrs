<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Asesmen Keperawatan IGD</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <VButton type="button" rounded outlined color="warning"
                                    :disabled="NOREC_EMRPASIEN == undefined" raised icon="lnir lnir-printer"
                                    @click="print()"> Cetak
                                </VButton>
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isLoading || isLoadingPasien" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body p-2">
                    <div class="business-dashboard hr-dashboard">
                        <div class="columns is-multiline">
                            <div class="column is-12" v-if="isLoadingPasien">
                                <PlaceloadHeader class="m-3" />
                            </div>
                            <div class="column is-12" v-if="!isLoadingPasien">
                                <VCard class="border-card pink">
                                    <TabView :scrollable="true" class="skuy">
                                        <TabPanel header="A. ASESMEN">
                                            <div class="column is-12">
                                                <VCard>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-5">
                                                            <h1 style="font-weight: bold;" class="pb-3">Respon Time</h1>
                                                            <VField>
                                                                <VControl>
                                                                    <VInput type="text" v-model="input.ResponTime"
                                                                        placeholder="Respon Time" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-4">
                                                            <h1 style="font-weight: bold;" class="pb-3">Waktu
                                                                Pemeriksaan
                                                            </h1>
                                                            <VField>
                                                                <VDatePicker v-model="input.waktuPemeriksaan"
                                                                    mode="dateTime" style="width: 100%" trim-weeks
                                                                    :max-date="new Date()">
                                                                    <template #default="{ inputValue, inputEvents }">
                                                                        <VField>
                                                                            <VControl icon="feather:calendar" fullwidth>
                                                                                <VInput :value="inputValue"
                                                                                    placeholder="Waktu Pemeriksaan"
                                                                                    v-on="inputEvents" />
                                                                            </VControl>
                                                                        </VField>
                                                                    </template>
                                                                </VDatePicker>
                                                            </VField>
                                                        </div>
                                                    </div>

                                                </VCard>
                                            </div>
                                            <div class="column is-12">
                                                <VCard>
                                                    <div class="column is-12">
                                                        <h1 style="font-weight: bold;">Jenis Kasus</h1>
                                                        <div class="columns is-mulitiline">
                                                            <div class="column is-2" v-for="(data) in JenisKasus">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.model]"
                                                                            :true-value="data.title" :label="data.title"
                                                                            color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="column is-12">
                                                        <h1 style="font-weight: bold;padding-bottom: 7px;">Transportasi
                                                            ke
                                                            IGD</h1>
                                                        <div class="columns is-multiline"
                                                            v-for="(datas) in transportasiIGD">
                                                            <div class="column pb-0 pt-0"
                                                                v-if="datas.type == 'checkbox'"
                                                                :class="datas.value.length > 2 ? 'is-2' : 'is-3'"
                                                                v-for="(data) in datas.value">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.model]"
                                                                            class="pb-0" :true-value="data.title"
                                                                            :label="data.title" color="primary"
                                                                            square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-4">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input[datas.opsional.model]" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="column is-12">
                                                        <h1 style="font-weight: bold;padding-bottom: 7px;">Riwayat
                                                            Kesehatan
                                                        </h1>
                                                        <div class="column is-12" v-for="(data) in riwayatKesehatan">
                                                            <p>{{ data.title }}</p>
                                                            <VField v-if="data.type == 'textbox'">
                                                                <VControl>
                                                                    <VInput type="text" v-model="input[data.model]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else>
                                                                <VControl>
                                                                    <VTextarea class="textarea"
                                                                        v-model="input[data.model]" rows="2"
                                                                        autocomplete="off" autocapitalize="off"
                                                                        spellcheck="true" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>

                                                    <div class="column is-12">
                                                        <h1 style="font-weight: bold;padding-bottom: 7px;">Riwayat
                                                            Alergi
                                                        </h1>
                                                        <div class="columns is-multiline">
                                                            <div class="column" v-for="(data) in riwayatAlergi"
                                                                :class="data.type == 'checkbox' ? 'is-2' : 'is-4'">
                                                                <VField v-if="data.type == 'checkbox'">
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.model]"
                                                                            :true-value="data.title" :label="data.title"
                                                                            color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                                <VField v-else>
                                                                    <VControl>
                                                                        <VInput type="text"
                                                                            v-model="input[data.model]" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="column is-12">
                                                        <h1 style="font-weight: bold;padding-bottom: 7px;">Riwayat
                                                            Penggunaan Obat-obatan</h1>
                                                        <VField>
                                                            <VControl>
                                                                <VTextarea class="textarea"
                                                                    v-model="input.riwayatObatObatan" rows="3"
                                                                    autocomplete="off" autocapitalize="off"
                                                                    spellcheck="true" />
                                                            </VControl>
                                                        </VField>
                                                    </div>

                                                    <div class="column is-12">
                                                        <h1 style="font-weight: bold;padding-bottom: 7px;">GCS</h1>
                                                        <div class="columns p-3">
                                                            <div class="column is-6 pb-0">
                                                                <div class="columns">
                                                                    <div class="column is-1">
                                                                        <h1 class="mb-3 emr"
                                                                            style="text-align: center;">No
                                                                        </h1>
                                                                    </div>
                                                                    <div class="column is-one-quarter"
                                                                        style="text-align: center;">
                                                                        <h1 class="mb-3 emr">Parameter</h1>
                                                                    </div>
                                                                    <div class="column is-5"
                                                                        style="text-align: center;">
                                                                        <h1 class="mb-3 emr">Nilai</h1>
                                                                    </div>
                                                                </div>
                                                                <div class="columns pb-4" v-for="(data) in kesadaran">
                                                                    <div class="column is-1 pt-0 "
                                                                        style="text-align: center;">
                                                                        <h1 class="mb-3 emr"> {{ data.no }}</h1>
                                                                    </div>
                                                                    <div class="column  is-one-quarter pt-0"
                                                                        style="text-align: center;">
                                                                        <h1 class="mb-3 emr">{{ data.parameter }}</h1>
                                                                    </div>
                                                                    <div class="column is-5" style="text-align: end;">
                                                                        <div class="columns is-multiline pb-5">
                                                                            <div class="column is-4 pt-0"
                                                                                v-for="(pilihan) in data.nilai">
                                                                                <VField>
                                                                                    <VControl raw subcontrol>
                                                                                        <VCheckbox class="p-0"
                                                                                            :true-value="pilihan.poin"
                                                                                            v-model="input[pilihan.model]"
                                                                                            :label="pilihan.poin"
                                                                                            color="primary" />
                                                                                    </VControl>
                                                                                </VField>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="column is-6">
                                                                <VCard>
                                                                    <p>KETERANGAN</p>
                                                                    <div class="column p-0"
                                                                        v-for="(data) in descRangeKesadaran">
                                                                        <VField>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="pb-0"
                                                                                    :true-value="data.value"
                                                                                    v-model="input[data.model]"
                                                                                    :label="data.des" color="primary"
                                                                                    disabled />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </VCard>
                                                            </div>
                                                        </div>
                                                        <div class="column is-4 pt-0" style="margin-left: auto;">
                                                            <VField>
                                                                <h1 style="font-weight: bold;" class="mb-2">JUMLAH TOTAL
                                                                </h1>
                                                                <VControl>
                                                                    <input v-model="input.totalKesadaran" class="input"
                                                                        placeholder="Jumlah" disabled />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>

                                                    <div class="column is-12">
                                                        <h1 style="font-weight: bold;padding-bottom: 7px;">Keadaan Umum
                                                        </h1>
                                                        <div class="columns is-mulitiline">
                                                            <div class="column is-2" v-for="(data) in keadaanUmum">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.model]"
                                                                            :true-value="data.title" :label="data.title"
                                                                            color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="column is-12">
                                                        <h1 style="font-weight: bold;padding-bottom: 7px;">Tanda tanda
                                                            vital
                                                        </h1>
                                                        <div class="columns is-multiline pl-3 pr-3">
                                                            <div class="column is-3" v-for="(data) in tandaVital">
                                                                <p class="mb-3">{{ data.title }}</p>
                                                                <VField addons v-if="data.satuan">
                                                                    <VControl expanded>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input[data.model]" />
                                                                    </VControl>
                                                                    <VControl class="field-addon-body">
                                                                        <VButton static>{{ data.satuan }}</VButton>
                                                                    </VControl>
                                                                </VField>
                                                                <VField v-else>
                                                                    <VControl raw subcontrol>
                                                                        <input v-model="input[data.model]"
                                                                            class="input" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </VCard>
                                            </div>

                                        </TabPanel>
                                        <TabPanel header="B. PENGKAJIAN NYERI">
                                            <VCard class="border-card blue">
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;">VAS </h1>
                                                    <div class="column is-8">
                                                        <h1 style="font-weight: bold; text-align: center;" class="mb-4">
                                                            {{
                                                                input.skor }}</h1>
                                                        <Slider v-model="input.vas" :min="0" :max="10" />
                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;">Kualitas Nyeri</h1>
                                                    <div class="columns is-mulitiline">
                                                        <div class="column is-3" v-for="(data) in kualitasNyeri">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>

                                                    </div>
                                                    <div class="column is-4" style="margin-top: -1rem;">
                                                        <VField>
                                                            <VControl>
                                                                <VInput type="text" v-model="input.kualitasLainnya"
                                                                    placeholder="Kualitas Lainnya" />
                                                            </VControl>
                                                        </VField>

                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;padding-bottom: 7px;">Menjalar</h1>
                                                    <div class="columns is-multiline" v-for="(datas) in Menjalar">
                                                        <div class="column pb-0 pt-0" v-if="datas.type == 'checkbox'"
                                                            :class="datas.value.length > 2 ? 'is-2' : 'is-3'"
                                                            v-for="(data) in datas.value">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model]" class="pb-0"
                                                                        :true-value="data.title" :label="data.title"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-4">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[datas.opsional.model]" />
                                                                </VControl>

                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;">Frekuensi Nyeri</h1>
                                                    <div class="columns is-mulitiline">
                                                        <div class="column is-3" v-for="(data) in listfrekuensiNyeri">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>

                                            </VCard>
                                        </TabPanel>
                                        <TabPanel header="C. SKALA RISIKO JATUH">
                                            <VCard class="border-card blue">
                                                <!-- <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-12 p-0">
                                                            <p>1.&nbsp Perhatikan cara berjalan pasien saat akan
                                                                duduk
                                                                dikursi, apakah pasien tampak
                                                                seimbang(sempoyongan/limbung) ?</p>
                                                            <div class="is-flex">
                                                                <VField v-for="items in pilihan" :key="items.value">
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input.caraJalanNormalTidak"
                                                                            :true-value="items.label" :label="items.label"
                                                                            color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>

                                                        <div class="column is-12 p-0">
                                                            <p>2.&nbsp Apakah pasien memegang pinggiran kursi atau
                                                                meja atau benda lain sebagai penopang saat akan duduk ?</p>
                                                            <div class="columns is-multiline">
                                                                <div class="columns is-4">
                                                                <VField v-for="items in pilihan" :key="items.value">
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input.caradudukNormalTidak"
                                                                            :true-value="items.label" :label="items.label"
                                                                            color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                        </div>

                                                        <div class="column is-12 p-0">

                                                            <p>3.&nbsp Hasil</p>
                                                            <VField v-for="items in hasil">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input.hasilResikoJatuh"
                                                                        :true-value="items.label" :label="items.label"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>

                                                        </div>

                                                    </div>
                                                </div> -->
                                                <div class="column is-12 pb-1" v-for="(datas) in resikoJatuh">
                                                    <h1 style="font-weight : bold;">{{ datas.title }}</h1>
                                                    <div class="columns is-multiline pt-1">
                                                        <div class="column is-4" v-for="(data) in datas.value">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model]"
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle" class="pl-2 pt-1"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </VCard>
                                        </TabPanel>
                                        <TabPanel header="D. SKRINING GIZI AWAL">
                                            <VCard class="border-card blue">
                                                <div class="column is-12">

                                                    <h1 style="font-weight : bold;">Malnutrition Screening Tools (MST)
                                                    </h1>
                                                    <div class="column is-12">
                                                        <div class="columns is-multiline"
                                                            style="border-bottom: 1px solid;">
                                                            <div class="column is-1">
                                                                <h1 style="font-weight : bold;">No</h1>
                                                            </div>
                                                            <div class="column is-5">
                                                                <h1 style="font-weight : bold;">Parameter</h1>
                                                            </div>
                                                            <div class="column is-5"></div>
                                                            <div class="column">
                                                                <h1 style="font-weight : bold;">Nilai</h1>
                                                            </div>
                                                        </div>
                                                        <div class="columns is-multiline"
                                                            v-for="(data) in resikoNutrisional.pertama">
                                                            <div class="column is-1">
                                                                <h1 class="emr">{{ data.no }}</h1>
                                                            </div>
                                                            <div class="column is-5">
                                                                <h1 class="emr">{{ data.parameter }}</h1>
                                                            </div>
                                                            <div class="column is-5 pt-0">
                                                                <div class="column is-12 pb-0"
                                                                    v-for="(value) in data.pengkajian">
                                                                    <VField class="">
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input[value.model]"
                                                                                class="p-0" :true-value="value.value"
                                                                                :label="value.title" color="primary"
                                                                                square />
                                                                        </VControl>
                                                                        <small>{{ value.keterangan }}</small>
                                                                    </VField>

                                                                </div>
                                                            </div>
                                                            <div class="column pt-0">
                                                                <div class="column pb-0"
                                                                    v-for="(nilai) in data.pengkajian">
                                                                    <h1 :class="nilai.keterangan ? 'emr mb-5' : 'emr'">
                                                                        {{ nilai.poin }}
                                                                    </h1>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="columns is-multiline"
                                                            v-for="(data) in resikoNutrisional.kedua">
                                                            <div class="column is-1">
                                                                <h1 class="emr">{{ data.no }}</h1>
                                                            </div>
                                                            <div class="column is-5">
                                                                <h1 class="emr">{{ data.parameter }}</h1>
                                                            </div>
                                                            <div class="column is-5 pt-0">
                                                                <div class="column is-12 pb-0"
                                                                    v-for="(value) in data.pengkajian">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input[value.model]"
                                                                                :true-value="value.value"
                                                                                :label="value.title" class="p-0"
                                                                                color="primary" square />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="column pt-0">
                                                                <div class="column pb-0"
                                                                    v-for="(nilai) in data.pengkajian">
                                                                    <h1 :class="nilai.keterangan ? 'emr mb-3' : 'emr'">
                                                                        {{ nilai.poin }}
                                                                    </h1>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="column is-3" style="margin-left: auto;">
                                                        <VField label="Jumlah Total">
                                                            <VControl raw subcontrol>
                                                                <input v-model="input.totalNilaiMST"
                                                                    class="input v-else" disabled />
                                                            </VControl>
                                                        </VField>
                                                    </div>

                                                    <div class="column is-12">
                                                        <VCard>
                                                            <h1 style="font-weight : bold;">Keterangan</h1>
                                                            <div class="column" v-for="(desc) in keteranganJumlah">
                                                                <div class="columns is-multiline">
                                                                    <div class="column is-3 p-0">
                                                                        <p>{{ desc.rangeNilai }}</p>
                                                                    </div>
                                                                    <div class="column is-4 p-0">
                                                                        <p>{{ desc.desc }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </VCard>
                                                    </div>

                                                </div>
                                            </VCard>

                                        </TabPanel>
                                        <TabPanel header="E. STATUS PSIKOLOGIS">
                                            <VCard class="border-class pink">
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Status Psikologis
                                                    </h1>
                                                    <div class="columns is-multiline pt-3 pl-3">
                                                        <div class="column is-6" v-for="(data, i) in psikologis">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model + i]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        class="p-0" color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>

                                                </div>

                                            </VCard>
                                        </TabPanel>
                                        <TabPanel header="F. PENGKAJIAN EDUKASI PASIEN DAN KELUARGA">
                                            <VCard class="border-class pink">
                                                <div class="column is-12 p-0">
                                                    <h1 style="font-weight:bold"> A. Kemampuan dan Kemauan Edukasi
                                                    </h1>
                                                    <div class="column is-12 p-0">
                                                        <div class="is-flex">
                                                            <div class="column is-4" style="margin-top:0.5rem">
                                                                <h1 style="font-weight: bold;"> Nama Panggilan : </h1>
                                                            </div>

                                                            <div class="column is-6" style="margin-left: -10rem">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            placeholder="Nama Panggilan"
                                                                            v-model="input.namaPanggilan" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12 p-0">
                                                        <div class="is-flex">
                                                            <div class="column is-4" style="margin-top:0.5rem">
                                                                <h1 style="font-weight: bold;"> Agama : </h1>
                                                            </div>
                                                            <div class="column is-6" style="margin-left: -10rem">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            placeholder="Agama" v-model="input.agama" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12 p-0">
                                                        <div class="is-flex">
                                                            <div class="column is-4" style="margin-top:0.5rem">
                                                                <h1 style="font-weight: bold;"> Nilai yang dianut :
                                                                </h1>
                                                            </div>
                                                            <div class="column is-6" style="margin-left: -10rem">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            placeholder="Nilai yang dianut"
                                                                            v-model="input.nilaiYangdianut" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12 p-0">
                                                        <div class="is-flex">
                                                            <div class="column is-4" style="margin-top:0.5rem">
                                                                <h1 style="font-weight: bold;"> Pendidikan : </h1>
                                                            </div>
                                                            <div class="column is-6" style="margin-left: -10rem">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            placeholder="Pendidikan"
                                                                            v-model="input.pendidikan" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12 p-0">
                                                        <div class="is-flex">
                                                            <div class="column is-4" style="margin-top:0.5rem">
                                                                <h1 style="font-weight: bold;"> Kesulitan Komunikasi :
                                                                </h1>
                                                            </div>
                                                            <div class="column is-4" style="margin-left: -10rem">
                                                                <div class="is-flex">
                                                                    <VField v-for="items in kesulitan"
                                                                        :key="items.value">
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.kesulitanKomunikasi"
                                                                                :true-value="items.value"
                                                                                :label="items.label" color="primary"
                                                                                square />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="column is-4"
                                                                style="margin-top:0.5rem; margin-left: -5rem">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            placeholder="Kesulitan Komunikasi"
                                                                            v-model="input.ketKesulitanKomunikasi" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12 p-0">
                                                        <div class="is-flex">
                                                            <div class="column is-4" style="margin-top:0.5rem">
                                                                <h1 style="font-weight: bold;"> Bahasa yang dipakai :
                                                                </h1>
                                                            </div>
                                                            <div class="column is-6" style="margin-left: -10rem">
                                                                <div class="is-flex">
                                                                    <VField v-for="items in bahasa" :key="items.value">
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.bahasa"
                                                                                :true-value="items.label"
                                                                                :label="items.label" color="primary"
                                                                                square />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="column is-2"
                                                                style="margin-top:0.5rem; margin-left: -5rem">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            placeholder="Bahasa sehari-hari"
                                                                            v-model="input.ketBahasa" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12 p-0">
                                                        <div class="is-flex">
                                                            <div class="column is-4" style="margin-top:0.5rem">
                                                                <h1 style="font-weight: bold;"> Penterjemah : </h1>
                                                            </div>
                                                            <div class="column is-4" style="margin-left: -10rem">
                                                                <div class="is-flex">
                                                                    <VField v-for="items in penterjemah"
                                                                        :key="items.value">
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.penterjemah"
                                                                                :true-value="items.value"
                                                                                :label="items.label" color="primary"
                                                                                square />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="column is-3" style="margin-top:0.5rem;">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            placeholder="Penterjemah"
                                                                            v-model="input.ketPenterjemah" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12">
                                                        <h1 style="font-weight: bold;"> Identifikasi dan berikan tanda
                                                            (V)
                                                            pada hambatan yang dapat mempengaruhi proses dan hasil
                                                            edukasi

                                                        </h1>
                                                        <div class="columns is-multiline pt-3 pl-3">
                                                            <div class="column is-6" v-for="(data, i) in hambatan">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.model + i]"
                                                                            :true-value="data.title" :label="data.title"
                                                                            class="p-0" color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-12">
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input"
                                                                        placeholder="Keterangan Hambatan Lainnya"
                                                                        v-model="input.keteranganHambatan" />
                                                                </VControl>
                                                            </div>
                                                        </div>
                                                        <div class="column is-12 p-0">
                                                            <div class="is-flex">
                                                                <div class="column is-6" style="margin-top:1rem">
                                                                    <h1 style="font-weight: bold;"> Kesediaan pasien
                                                                        /keluarga* untuk menerima informasi yang
                                                                        diberikan:
                                                                    </h1>
                                                                </div>
                                                                <div class="column is-4">
                                                                    <div class="is-flex">
                                                                        <VField v-for="items in pilihan"
                                                                            :key="items.value">
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox
                                                                                    v-model="input.kesediaanMenerimaInformasi"
                                                                                    :true-value="items.label"
                                                                                    :label="items.label" color="primary"
                                                                                    square />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr class="solid">
                                                    <div class="column is-12">
                                                        <h1 style="font-weight:bold"> B. Kebutuhan Edukasi
                                                        </h1>
                                                        <h1 style="font-weight:bold"> *Identifikasi dan berikan tanda
                                                            (V)
                                                            pada kebutuhan edukasi yang dibutuhkan pasien dan
                                                            keluarganya
                                                            *Pada Keadaan dimana pasien memerlukan edukasi suatu bidang
                                                            yang
                                                            khusus, Dokter Penanggungjawab Pasien akan menentukan
                                                            kebutuhan
                                                            dan program edukasi sesuai

                                                        </h1>
                                                        <div class="columns is-multiline pt-3 pl-3">
                                                            <div class="column is-6"
                                                                v-for="(data, i) in kebutuhanEdukasi">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.model + i]"
                                                                            :true-value="data.value" :label="data.title"
                                                                            class="p-0" color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-12">
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input"
                                                                        placeholder="Keterangan kebutuhan Edukasi"
                                                                        v-model="input.keterangankebutuhanEdukasi" />
                                                                </VControl>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </VCard>
                                        </TabPanel>
                                        <TabPanel header="G. DIAGNOSA KEPERAWATAN">
                                            <VCard class="border-card blue">
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Masalah Keperawatan
                                                    </h1>
                                                    <div class="columns is-multiline pt-3 pl-3">
                                                        <div class="column is-6"
                                                            v-for="(data, i) in masalahKeperawatan">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model + i]"
                                                                        :true-value="data.value" :label="data.nama"
                                                                        class="p-0" color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-12">
                                                            <VControl expanded>
                                                                <VInput type="text" class="input"
                                                                    placeholder="Keterangan Masalah Keperawatan"
                                                                    v-model="input.keteranganmasalahKeperawatan" />
                                                            </VControl>
                                                        </div>
                                                    </div>

                                                </div>

                                            </VCard>
                                            <VCard class="border-card blue" style="margin-top: 2rem;">
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Rencana
                                                    </h1>
                                                    <div class="columns is-multiline pt-3 pl-3">
                                                        <div class="column is-6" v-for="(data, i) in Rencana">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model + i]"
                                                                        :true-value="data.value" :label="data.nama"
                                                                        class="p-0" color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-12">
                                                            <VControl expanded>
                                                                <VInput type="text" class="input"
                                                                    placeholder="Keterangan Rencana"
                                                                    v-model="input.keteranganRencana" />
                                                            </VControl>
                                                        </div>
                                                    </div>

                                                </div>

                                            </VCard>
                                        </TabPanel>
                                        <TabPanel header="H. TINDAKAN YANG SUDAH DILAKUKAN DI IGD">
                                            <VCard class="border-card blue">
                                                <div class="column is-12">
                                                    <table class="tg">
                                                        <thead>
                                                            <tr>
                                                                <td class="tg-0lax text-center" rowspan="2">Tanggal/Jam
                                                                </td>
                                                                <td class="tg-0lax text-center" colspan="4">Tindakan
                                                                    yang
                                                                    sudah dilakukan IGD</td>

                                                            </tr>
                                                            <tr>
                                                                <td class="tg-0lax">Tindakan </td>
                                                                <td class="tg-0lax">Petugas Pelaksana </td>
                                                                <td class="tg-0lax">#</td>

                                                            </tr>
                                                        </thead>
                                                        <tbody v-for="(item, index) in input.details" :key="index">
                                                            <tr>
                                                                <td style="width:15%">
                                                                    <VField>
                                                                        <VControl class="prime-auto">
                                                                            <Calendar v-model="item.tgl"
                                                                                selectionMode="single"
                                                                                :manualInput="false" class="w-100"
                                                                                :showIcon="true" showTime
                                                                                hourFormat="24"
                                                                                :date-format="'yy-mm-dd'" />
                                                                        </VControl>
                                                                    </VField>
                                                                </td>

                                                                <td>
                                                                    <VField>
                                                                        <VControl>
                                                                            <VTextarea v-model="item.tindakan" rows="5"
                                                                                placeholder="Tindakan...">
                                                                            </VTextarea>
                                                                        </VControl>
                                                                    </VField>
                                                                </td>

                                                                <td style="width:15%">
                                                                    <VField>
                                                                        <VControl class="prime-auto">

                                                                            <AutoComplete
                                                                                v-model="item.dokterRawatBersama"
                                                                                :suggestions="d_Dokter"
                                                                                @complete="fetchDokter($event)"
                                                                                :optionLabel="'label'" :dropdown="true"
                                                                                :minLength="3" :appendTo="'body'"
                                                                                :loadingIcon="'pi pi-spinner'"
                                                                                :field="'label'"
                                                                                placeholder="Petugas Pelaksana..."
                                                                                class="mt-2" />
                                                                        </VControl>

                                                                    </VField>
                                                                </td>
                                                                <td rowspan="2"
                                                                    style="width:7%;vertical-align: middle;">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-6">
                                                                            <VIconButton type="button" raised circle
                                                                                icon="feather:plus"
                                                                                @click="addNewItem()" color="info"
                                                                                v-tooltip.bubble="'Tambah '">
                                                                            </VIconButton>
                                                                        </div>
                                                                        <div class="column is-6 ml-3-min">
                                                                            <VIconButton v-if="index > 0" type="button"
                                                                                raised circle icon="feather:trash"
                                                                                @click="removeItem(index)"
                                                                                color="danger">
                                                                            </VIconButton>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </VCard>
                                            <VCard class="border-card blue" style="margin-top:2rem;">
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Kondisi Pasien
                                                    </h1>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-2" v-for="items in KondisiPasien "
                                                            :key="items.value">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.KondisiPasien"
                                                                    :true-value="items.nama" :label="items.nama"
                                                                    color="primary" square />
                                                            </VControl>
                                                        </div>
                                                    </div>

                                                </div>
                                            </VCard>

                                            <VCard class="border-card blue" style="margin-top: 2rem;">
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> Tindak Lanjut
                                                    </h1>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-2" v-for="items in TindakLanjut "
                                                            :key="items.value">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.tindakLanjut"
                                                                    :true-value="items.nama" :label="items.nama"
                                                                    color="primary" square />
                                                            </VControl>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-12 p-0">
                                                    <div class="is-flex">
                                                        <div class="column is-4" style="margin-top:0.5rem">
                                                            <h1 style="font-weight: bold;"> Saat Pasien keluar Pindah Ke
                                                                Ruangan: </h1>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VField>
                                                                <VDatePicker v-model="input.tanggalPasienKeluar"
                                                                    mode="dateTime" style="width: 100%" trim-weeks
                                                                    :max-date="new Date()">
                                                                    <template #default="{ inputValue, inputEvents }">
                                                                        <VField>
                                                                            <VControl icon="feather:calendar" fullwidth>
                                                                                <VInput :value="inputValue"
                                                                                    placeholder="Tanggal"
                                                                                    v-on="inputEvents" />
                                                                            </VControl>
                                                                        </VField>
                                                                    </template>
                                                                </VDatePicker>
                                                            </VField>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="column is-12 p-0">
                                                    <div class="is-flex">
                                                        <div class="column is-4" style="margin-top:0.5rem">
                                                            <h1 style="font-weight: bold;"> Kondisi Masalah : </h1>
                                                        </div>
                                                        <div class="column is-6" style="margin-left: -10rem">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput type="text" class="input"
                                                                        placeholder="Kondisi Masalah"
                                                                        v-model="input.kondisiMasalah" />
                                                                </VControl>

                                                            </VField>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="column is-12 p-0">
                                                    <div class="is-flex">
                                                        <div class="column is-4" style="margin-top:0.5rem">
                                                            <h1 style="font-weight: bold;"> Tingkat Kesadaran : </h1>
                                                        </div>
                                                        <div class="column is-6" style="margin-left: -10rem">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput type="text" class="input"
                                                                        placeholder="Tingkat Kesadaran"
                                                                        v-model="input.tingkatKesadaran" />
                                                                </VControl>

                                                            </VField>
                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold;"> CSG </h1>

                                                    <div class="columns p-3">
                                                        <div class="column is-6 pb-0">
                                                            <div class="columns">
                                                                <div class="column is-1">
                                                                    <h1 class="mb-3 emr" style="text-align: center;">No
                                                                    </h1>
                                                                </div>
                                                                <div class="column is-one-quarter"
                                                                    style="text-align: center;">
                                                                    <h1 class="mb-3 emr">Parameter</h1>
                                                                </div>
                                                                <div class="column is-one-quarter"
                                                                    style="text-align: center;">
                                                                    <h1 class="mb-3 emr">Nilai</h1>
                                                                </div>
                                                            </div>
                                                            <div class="columns pb-4" v-for="(data) in kesadaran">
                                                                <div class="column is-1 pt-0"
                                                                    style="text-align: center;">
                                                                    <h1 class="mb-3 emr"> {{ data.no }}</h1>
                                                                </div>
                                                                <div class="column  is-one-quarter pt-0"
                                                                    style="text-align: center;">
                                                                    <h1 class="mb-3 emr">{{ data.parameter }}</h1>
                                                                </div>
                                                                <div class="column  is-one-quarter pt-1"
                                                                    style="text-align: center;">
                                                                    <VField v-for="(pilihan) in data.nilai">
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox class="pb-0"
                                                                                :true-value="pilihan.poin"
                                                                                v-model="input[pilihan.model]"
                                                                                :label="pilihan.poin" color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>

                                                            </div>
                                                            <hr>
                                                        </div>
                                                        <div class="column is-6">
                                                            <VCard>
                                                                <p>KETERANGAN</p>
                                                                <div class="column"
                                                                    v-for="(data) in descRangeKesadaran">
                                                                    <VField>
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox class="pb-0"
                                                                                :true-value="data.value"
                                                                                v-model="input[data.model]"
                                                                                :label="data.des" color="primary" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </VCard>
                                                        </div>
                                                    </div>
                                                    <div class="column is-4 pt-0" style="margin-left: auto;">
                                                        <VField>
                                                            <h1 style="font-weight: bold;" class="mb-2">JUMLAH TOTAL
                                                            </h1>
                                                            <VControl>
                                                                <input v-model="input.totalKesadaran" class="input"
                                                                    placeholder="Jumlah" disabled />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-12">
                                                        <div class="flex">
                                                            <div class="column is-2">
                                                                <h1 style="font-weight: bold;">HR</h1>
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            placeholder="HR" v-model="input.nadi" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>
                                                            <div class="column is-2">
                                                                <h1 style="font-weight: bold;">RR</h1>
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            placeholder="RR"
                                                                            v-model="input.pernapasan" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>
                                                            <div class="column is-2">
                                                                <h1 style="font-weight: bold;">SPO2</h1>
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            placeholder="SPO2" v-model="input.spo2" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>
                                                            <div class="column is-2">
                                                                <h1 style="font-weight: bold;">S</h1>
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            placeholder="S" v-model="input.suhu" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-2">
                                                                <h1 style="font-weight: bold;">TD</h1>
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            placeholder="TD"
                                                                            v-model="input.tekananDarah" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>

                                                    </div>
                                                    <div class="column is-12">
                                                        <h1 style="text-align: center; font-weight: bold;"> SERAH TERIMA
                                                            DOKUMEN PENUNJANG</h1>
                                                    </div>
                                                    <div class="column is-12 p-0">
                                                        <div class="flex">
                                                            <div class="column is-8">
                                                                <h1 style="text-align: left; font-weight: bold;">
                                                                    Dokumen yang diserahterimakan</h1>
                                                            </div>
                                                            <div class="column is-4">
                                                                <h1 style="text-align: left; font-weight: bold;">
                                                                    Keterangan</h1>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="column is-12 pt-0">
                                                        <div class="is-flex">
                                                            <div class="column is-4" style="margin-top:0.5rem">
                                                                <h1 style="font-weight: bold;"> 1. EKG </h1>
                                                            </div>
                                                            <div class="column is-4">
                                                                <div class="is-flex">
                                                                    <VField v-for="items in pilihan" :key="items.value">
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.dokumenEKG"
                                                                                :true-value="items.label"
                                                                                :label="items.label" color="primary"
                                                                                square />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="column is-4" style="margin-top:0.5rem;">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input" placeholder=""
                                                                            v-model="input.ketDokumenEKG" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12 pt-0">
                                                        <div class="is-flex">
                                                            <div class="column is-4" style="margin-top:0.5rem">
                                                                <h1 style="font-weight: bold;"> 2. Radiologi </h1>
                                                            </div>
                                                            <div class="column is-4">
                                                                <div class="is-flex">
                                                                    <VField v-for="items in pilihan" :key="items.value">
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.dokumenRadiologi"
                                                                                :true-value="items.label"
                                                                                :label="items.label" color="primary"
                                                                                square />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="column is-4" style="margin-top:0.5rem;">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input" placeholder=""
                                                                            v-model="input.ketDokumenRadiologi" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12 pt-0">
                                                        <div class="is-flex">
                                                            <div class="column is-4" style="margin-top:0.5rem">
                                                                <h1 style="font-weight: bold;"> 3. Laboratorium </h1>
                                                            </div>
                                                            <div class="column is-4">
                                                                <div class="is-flex">
                                                                    <VField v-for="items in pilihan" :key="items.value">
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox
                                                                                v-model="input.dokumenLaboratorium"
                                                                                :true-value="items.label"
                                                                                :label="items.label" color="primary"
                                                                                square />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="column is-4" style="margin-top:0.5rem;">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input" placeholder=""
                                                                            v-model="input.ketDokumenLaboratorium" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12 pt-0">
                                                        <div class="is-flex">
                                                            <div class="column is-4" style="margin-top:0.5rem">
                                                                <h1 style="font-weight: bold;"> 4. Rujukan </h1>
                                                            </div>
                                                            <div class="column is-4">
                                                                <div class="is-flex">
                                                                    <VField v-for="items in pilihan" :key="items.value">
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input.dokumenRujukan"
                                                                                :true-value="items.label"
                                                                                :label="items.label" color="primary"
                                                                                square />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                            <div class="column is-4" style="margin-top:0.5rem;">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input" placeholder=""
                                                                            v-model="input.ketDokumenRujukan" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12 pt-0">
                                                        <div class="is-flex">
                                                            <div class="column is-4" style="margin-top:0.5rem">
                                                                <h1 style="font-weight: bold;"> 5. Lain-lain </h1>
                                                            </div>
                                                            <div class="column is-4" style="margin-top:0.5rem;">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input" placeholder=""
                                                                            v-model="input.ketLainlain" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>
                                                            <div class="column is-4" style="margin-top:0.5rem;">
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input" placeholder=""
                                                                            v-model="input.ketLainLainnya" />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </VCard>
                                        </TabPanel>

                                    </TabView>

                                    <div class="column is-4 mt-3" style="margin-left: auto;">
                                        <VCard class="border-card pink">
                                            <div class="column is-9">
                                                <VField>
                                                    <h1 style="font-weight: bold;">Jam Keluar IGD</h1>
                                                </VField>
                                                <VField>
                                                    <VDatePicker v-model="input.jamKeluarIGD" mode="dateTime"
                                                        style="width: 100%" trim-weeks :max-date="new Date()">
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VField>
                                                                <VControl icon="feather:calendar" fullwidth>
                                                                    <VInput :value="inputValue"
                                                                        placeholder="Jam Keluar IGD"
                                                                        v-on="inputEvents" />
                                                                </VControl>
                                                            </VField>
                                                        </template>
                                                    </VDatePicker>
                                                </VField>
                                            </div>
                                            <div class="column pt-0">
                                                <VField>
                                                    <h1 style="font-weight: bold;">Penanggung Jawab Pasien</h1>
                                                </VField>
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:search">
                                                        <AutoComplete v-model="input.penangungJawab"
                                                            :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                            :field="'label'" placeholder="ketik nama petugas" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </VCard>
                                    </div>
                                </VCard>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
import { useUserSession } from '/@src/stores/userSession'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import AutoComplete from 'primevue/autocomplete';
import Slider from 'primevue/slider';
import Fieldset from 'primevue/fieldset';
import Calendar from 'primevue/calendar';
import * as EMR from '../page-emr-plugins/asesmen-keperawatan-igd'



useHead({
    title: 'Asesmen Keperawatan IGD - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let JenisKasus = ref(EMR.JenisKasus())
let transportasiIGD = ref(EMR.transportasiIGD())
let riwayatKesehatan = ref(EMR.riwayatKesehatan())
let riwayatAlergi = ref(EMR.riwayatAlergi())
let tandaVital = ref(EMR.tandaVital())
let keadaanUmum = ref(EMR.keadaanUmum())
let kualitasNyeri = ref(EMR.kualitasNyeri())
let Menjalar = ref(EMR.Menjalar())
let listfrekuensiNyeri = ref(EMR.listfrekuensiNyeri())
let resikoJatuh = ref(EMR.resikoJatuh())
let resikoNutrisional = ref(EMR.listNutrisional())
let keteranganJumlah = ref(EMR.descripJumlah())
let psikologis = ref(EMR.psikologis())
let kesulitan = ref(EMR.kesulitan())
let bahasa = ref(EMR.bahasa())
let penterjemah = ref(EMR.penterjemah())
let hambatan = ref(EMR.hambatan())
let kebutuhanEdukasi = ref(EMR.kebutuhanEdukasi())
let Rencana = ref(EMR.Rencana())
let masalahKeperawatan = ref(EMR.masalahKeperawatan())
let KondisiPasien = ref(EMR.KondisiPasien())
let TindakLanjut = ref(EMR.TindakLanjut())
let kesadaran = ref(EMR.kesadaran())
let descRangeKesadaran = ref(EMR.descRangeKesadaran())


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

console.log(props.registrasi)
const pasien: any = ref({})
const d_Dokter: any = ref([])
const isLoadingPasien: any = ref(false)
const isDisabled: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
})


const COLLECTION: any = ref('AsesmenKeperawatanIGD') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    waktuPemeriksaan: new Date(),
    hasilResikoJatuh: 'Tidak berisiko (tidak ditemukan a dan b)',
    perluPenompang: null,
    caraBerjalanNormalatauTidak: null,
    details: [{
        no: 1,
        tgl: new Date(),
    }]
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})




const pilihan: any = ref([
    { label: 'Ya', value: 'Ya' },
    { label: 'Tidak', value: 'Tidak' },
])

const isLoading = ref(false)
const d_Petugas = ref([])


const loadRiwayat = () => {
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                isDisabled.value = false
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                } else { }
            } else {
                isDisabled.value = true
            }
        })
}

const countRangeNilai = (e: any) => {

    let cmc = {
        "keterangan": "CMC (14-15)",
        "poin": 15
    }
    let apatis = {
        "keterangan": "Apatis (12-13)",
        "poin": 13
    }
    let somnolen = {
        "keterangan": "Somnolen (10-11)",
        "poin": 11
    }
    let delirium = {
        "keterangan": "Delirium (7-9)",
        "poin": 9
    }
    let stupar = {
        "keterangan": "Stupar (4-6)",
        "poin": 6
    }
    let koma = {
        "keterangan": "Koma ( <= 3)",
        "poin": 3
    }

    descRangeKesadaran.value.forEach((elements: any) => {
        if (e <= 3 && e <= elements.value.poin) {
            input.value.rangeKesadaran = koma
        }
        else if (e <= 6 && e <= elements.value.poin) {
            input.value.rangeKesadaran = stupar
        }
        else if (e <= 9 && e <= elements.value.poin) {
            input.value.rangeKesadaran = delirium
        }
        else if (e <= 11 && e <= elements.value.poin) {
            input.value.rangeKesadaran = somnolen
        }
        else if (e <= 13 && e <= elements.value.poin) {
            input.value.rangeKesadaran = apatis
        }
        else if (e > 13 && e > elements.value.poin) {
            input.value.rangeKesadaran = cmc
        }
    })
}




const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
}

const simpan = () => {
    let object: any = {}
    let ID = input.value.id ? input.value.id : ''

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
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const addNewItem = () => {
    input.value.details.push({
        no: input.value.details[input.value.details.length - 1].no + 1,
        tgl: new Date(),
    });
}
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
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
        input.value.RR = response.pernapasan
        input.value.suhu = response.suhu
        input.value.nadi = response.nadi
        input.value.SPO2 = response.SPO2
        input.value.spo2 = response.SPO2
    })
}

const print = async () => {
    H.printBlade(`emr/cetak-asesmen-keperawatan-igd?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

watch(() =>
    input.value.vas,
    () => {

        if (input.value.vas == 0) {
            input.value.skor = input.value.vas + " = Tidak Nyeri"
        } else if (input.value.vas <= 3) {
            input.value.skor = input.value.vas + " = Nyeri Ringan"
        } else if (input.value.vas <= 6) {
            input.value.skor = input.value.vas + " = Nyeri Sedang"
        } else {
            input.value.skor = input.value.vas + " = Nyeri Berat"
        }
    })

watch(() => [
    input.value.kesadaranE,
    input.value.kesadaranM,
    input.value.kesadaranV,
    // input.value.point2
], () => {
    let poin1 = input.value.kesadaranE ? parseInt(input.value.kesadaranE) : 0
    let poin2 = input.value.kesadaranM ? parseInt(input.value.kesadaranM) : 0
    let poin3 = input.value.kesadaranV ? parseInt(input.value.kesadaranV) : 0
    const jumlahNilai = poin1 + poin2 + poin3
    countRangeNilai(jumlahNilai)
    input.value.totalKesadaran = jumlahNilai
})


watch(() => [
    input.value.penurunanBB,
    input.value.penurunanNafsuMakan,
], () => {

    let poin1 = input.value.penurunanBB ? parseInt(input.value.penurunanBB.poin) : 0
    let poin2 = input.value.penurunanNafsuMakan ? parseInt(input.value.penurunanNafsuMakan.poin) : 0

    const total = poin1 + poin2
    input.value.totalNilaiMST = total

})

// useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=TriagePasienIGD" + "&field=TBeGCS,TBvGCS,TBmGCS")
//     .then((response) => {
//         console.log(response.TBeGCS)
//         if (response != null) {
//             input.kesadaranE = response.TBeGCS;
//             input.kesadaranM = response.TBmGCS;
//             input.kesadaranV = response.TBvGCS;
//         } else {

//         }
//     })
watch([() => input.value.caraBerjalanNormalatauTidak, () => input.value.perluPenompang], ([caraduduk, kursi]) => {
  if (caraduduk === 'Tidak' && kursi === 'Tidak') {
    input.value.hasilResikoJatuh = "Tidak berisiko (tidak ditemukan a dan b)";
  } else if (caraduduk === 'Ya' && kursi === 'Ya') {
    input.value.hasilResikoJatuh = "Risiko tinggi (ditemukan a dan b)";
  } else if (caraduduk === 'Tidak' || kursi === 'Ya') {
    input.value.hasilResikoJatuh = "Risiko rendah-sedang (ditemukan a atau b)";
  } else if (caraduduk === 'Ya' || kursi === 'Tidak') {
    input.value.hasilResikoJatuh = "Risiko rendah-sedang (ditemukan a atau b)";
  } else {
    input.value.hasilResikoJatuh = "Tidak berisiko (tidak ditemukan a dan b)";
  }
});
getDataExist()
fetchPasien()
loadRiwayat()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.skuy.p-tabview .p-tabview-panels {
    background: #ffffff;
    padding: 1rem;
    border: 0 none;
    color: red;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
}

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
    text-align: left;
    vertical-align: top
}
</style>
