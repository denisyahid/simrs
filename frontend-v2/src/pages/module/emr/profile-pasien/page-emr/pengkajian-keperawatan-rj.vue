<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3> Pengkajian Keperawatan Pasien Rawat Jalan</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
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

                                    <div class="columns is-multiline p-3">

                                        <div class="column is-4">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;"> Tanggal</h1>
                                            <VField>
                                                <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%"
                                                    trim-weeks :max-date="new Date()">
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
                                        <div class="column is-4">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Jam Pasien Datang</h1>
                                            <VField>
                                                <VDatePicker v-model="input.jamDatang" mode="dateTime" style="width: 100%"
                                                    trim-weeks :max-date="new Date()">
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
                                        <div class="column is-4">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Jam Pengkajian</h1>
                                            <VField>
                                                <VDatePicker v-model="input.jamPengkajian" mode="dateTime"
                                                    style="width: 100%" trim-weeks :max-date="new Date()">
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
                                        <div class="column is-5">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Bahasa yang digunakan
                                                sehari-hari</h1>
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" v-model="input.bahasaPasien"
                                                        placeholder="Bahasa yang digunakan sehari-hari" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Riwayat Alergi</h1>
                                            <div class="columns is-multiline">
                                                <VField>
                                                    <VControl>
                                                        <VCheckbox v-model="input.alergi" true-value="Tidak" label="Tidak"
                                                            color="primary" square />
                                                    </VControl>
                                                </VField>
                                                <VField>
                                                    <VControl>
                                                        <VCheckbox v-model="input.alergi" true-value="Ya" label="Ya"
                                                            color="primary" square />
                                                    </VControl>

                                                </VField>
                                            </div>
                                        </div>
                                        <div class="column is-4" style="margin-top:2rem; margin-left: -9rem;">
                                            <Vfield>
                                                <VControl>
                                                    <VInput type="text" v-model="input.alergiDetail"
                                                        placeholder="Detail Alergi" />
                                                </VControl>
                                            </Vfield>
                                        </div>
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold;">Cara Bayar :</h1>
                                            <div class="columns is-multiline pt-3 pl-3">
                                                <div class="column is-2" v-for="(data, i) in caraBayar">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model + i]"
                                                                :true-value="data.title" :label="data.title" class="p-0"
                                                                color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4" style="margin-left: -2rem; margin-top:-0.5rem">
                                                    <Vfield>
                                                        <VControl>
                                                            <VInput type="text" v-model="input.detailCaraBayar"
                                                                placeholder="Cara Bayar Lainnya" />
                                                        </VControl>
                                                    </Vfield>
                                                </div>

                                            </div>
                                        </div>


                                        <div class="column is-12">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Keluhan Utama</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea class="textarea" v-model="input.keluhanUtama" rows="2"
                                                        placeholder="Keluhan Utama ..." autocomplete="off"
                                                        autocapitalize="off" spellcheck="true" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <Fieldset class="p-fieldsets" legend="Tanda Vital" :toggleable="true">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline p-3">
                                                        <div class="column is-3">
                                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Nadi</h1>
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input" placeholder="Nadi"
                                                                        v-model="input.nadi" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>x/menit</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-3">
                                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Tekanan
                                                                Darah</h1>
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input"
                                                                        placeholder="Tekanan Darah"
                                                                        v-model="input.tekananDarah" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>mmHG</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-3">
                                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Pernapasan
                                                            </h1>
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input"
                                                                        placeholder="pernafasan"
                                                                        v-model="input.pernapasan" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>x/menit</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-3">
                                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Suhu</h1>
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input" placeholder="Suhu"
                                                                        v-model="input.suhu" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>°C </VButton>
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-3">
                                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Skor Nyeri
                                                            </h1>
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input" placeholder="Skor Nyeri"
                                                                        v-model="input.skorNyeri" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>NIRS </VButton>
                                                                </VControl>
                                                            </VField>
                                                        </div>


                                                        <div class="column is-9">
                                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Tindak
                                                                Lanjut
                                                            </h1>
                                                            <div class="columns is-multiline">
                                                                <div class="column" v-for="(data) in tindakLanjut"
                                                                    :class="data.title === 'Tindak Lanjut' || data.type == 'textbox' ? 'is-4' : 'is-3'">
                                                                    <VField v-if="data.type == 'checkbox'">
                                                                        <VControl raw subcontrol>
                                                                            <VCheckbox v-model="input[data.model]"
                                                                                :true-value="data.title" :label="data.title"
                                                                                color="primary" square />
                                                                        </VControl>
                                                                    </VField>

                                                                    <VField v-else :label="data.title">
                                                                        <VControl raw subcontrol>
                                                                            <input v-model="input[data.model]"
                                                                                class="input pt-0" />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="columns is-multiline is-12 pl-5 pr-5 pt-5 pb-0">
                                                    <div class="column is-7">
                                                        <h1 style="font-weight: bold;">Skoring nyeri (Wong Baker Faces)</h1>
                                                        <div class="columns pt-4">
                                                            <div class="column" style="text-align: center;"
                                                                v-for="(image, i) in listImageNyeri.detail">
                                                                <VAvatar size="medium" :picture="image.img"
                                                                    :class="isAktive == i ? 'active' : ''"
                                                                    @click="test(image, i)" />
                                                                <p>{{ image.descNilai }}</p>
                                                                <p>{{ image.nama }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="column is-5">
                                                        <h1 style="font-weight: bold;">Score</h1>
                                                        <div class="pt-4">
                                                            <VField v-for="(skor) in listSkoringNyeri.detail">
                                                                <VControl raw subcontrol class="p-0">
                                                                    <VCheckbox class="pt-0" v-model="input.skor"
                                                                        :true-value="skor.descNilai" :label="skor.nama"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>


                                            </Fieldset>
                                        </div>

                                        <div class="column is-12">
                                            <Fieldset class="p-fieldsets" legend="Resiko Jatuh" :toggleable="true">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">

                                                        <div class="column is-12">
                                                            <h1 style="font-weight: bold;">1.&nbsp Perhatikan cara
                                                                berjalan pasien saat akan
                                                                duduk
                                                                dikursi, apakah pasien tampak
                                                                seimbang(sempoyongan/limbung) ?</h1>
                                                            <div class="columns is-mulitiline">
                                                                <div class="column is-2" v-for="(data) in Pilihan">
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

                                                            <h1 style="font-weight: bold;">2.&nbsp Apakah pasien
                                                                memegang pinggiran kursi atau
                                                                meja
                                                                atau benda lain sebagai penopang saat akan duduk ?
                                                            </h1>
                                                            <div class="columns is-mulitiline">
                                                                <div class="column is-2"
                                                                    v-for="(data) in pegangKursisaatDuduk">
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

                                                            <h1 style="font-weight: bold;">3.&nbsp Hasil
                                                            </h1>
                                                            <div class="columns is-mulitiline">
                                                                <div class="column is-4" v-for="(data) in hasilResikoJatuh">
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

                                                    </div>
                                                </div>
                                            </Fieldset>
                                        </div>
                                        <div class="column is-12">
                                            <Fieldset class="p-fieldsets" legend="Status Nutrisi" :toggleable="true">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-2">
                                                            <VField>
                                                                <h1 style="font-weight: bold;">Berat Badan </h1>
                                                            </VField>
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input"
                                                                        placeholder="Berat Badan"
                                                                        v-model="input.beratBadan" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>kg</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-2">
                                                            <VField>
                                                                <h1 style="font-weight: bold;">Tinggi Badan </h1>
                                                            </VField>
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input"
                                                                        placeholder="Berat Badan"
                                                                        v-model="input.tinggiBadan" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>cm</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-2">
                                                            <VField>
                                                                <h1 style="font-weight: bold;">IMT </h1>
                                                            </VField>
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input" placeholder="IMT"
                                                                        v-model="input.IMT" />
                                                                </VControl>

                                                            </VField>
                                                        </div>
                                                        <div class="column is-3">
                                                            <VField>
                                                                <h1 style="font-weight: bold;">Lingkar Kepala </h1>
                                                            </VField>
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input"
                                                                        placeholder="Lingkar Kepala"
                                                                        v-model="input.lingkarKepala" />
                                                                </VControl>

                                                            </VField>
                                                        </div>
                                                        <div class="column is-3">
                                                            <VField>
                                                                <h1 style="font-weight: bold;">Lingkar Lengan Atas</h1>
                                                            </VField>
                                                            <VField addons>
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input"
                                                                        placeholder="Lingkar Lengan Atas"
                                                                        v-model="input.lingkarLengan" />
                                                                </VControl>

                                                            </VField>
                                                        </div>
                                                        <div class="column is-12">
                                                            <h1 style="font-weight: bold;"> Skrinning Nutrisi (Berdasarkan
                                                                Malnutrition Skrinning Tool)
                                                            </h1>
                                                        </div>
                                                        <div class="column is-8">
                                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Hasil
                                                                Skrining
                                                            </h1>
                                                            <div class="columns is-multiline">
                                                                <VField>
                                                                    <VControl>
                                                                        <VCheckbox v-model="input.hasilSkrining"
                                                                            true-value="Total Skor >=2"
                                                                            label="Total Skor >=2" color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                                <VField>
                                                                    <VControl>
                                                                        <VCheckbox v-model="input.hasilSkrining"
                                                                            true-value="Total Skor < 2"
                                                                            label="Total Skor < 2" color="primary" square />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>
                                                        <div class="column is-8">
                                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Tindak
                                                                Lanjut
                                                            </h1>
                                                            <div class="columns is-multiline">
                                                                <VField>
                                                                    <VControl>
                                                                        <VCheckbox v-model="input.tindakLanjutNutrisi"
                                                                            true-value="Edukasi" label="Edukasi"
                                                                            color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                                <VField>
                                                                    <VControl>
                                                                        <VCheckbox v-model="input.tindakLanjutNutrisi"
                                                                            true-value="Rujuk ke Ahli Gizi"
                                                                            label="Rujuk ke Ahli Gizi" color="primary"
                                                                            square />
                                                                    </VControl>

                                                                </VField>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </Fieldset>
                                        </div>
                                        <div class="column is-12">
                                            <Fieldset class="p-fieldsets" legend="Status Fungsional" :toggleable="true">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-4" v-for="(data) in ListStatus">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" v-model="input[data.model]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-3">
                                                            <VControl expanded>
                                                                <VInput type="text" class="input"
                                                                    placeholder="Keterangan Status Fungsional"
                                                                    v-model="input.keteranganStatusFungsional" />
                                                            </VControl>
                                                        </div>

                                                    </div>
                                                </div>

                                            </fieldset>
                                        </div>
                                        <div class="column is-12">
                                            <Fieldset class="p-fieldsets" legend="Data Psikososial" :toggleable="true">
                                                <div class="column is-12">

                                                    <h1 style="font-weight: bold;margin-bottom: 1rem;">Ekpresi Muka</h1>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-4" v-for="(data) in ekpresiMuka">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" v-model="input[data.model]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-12">

                                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Kemampuan Bicara
                                                    </h1>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-4" v-for="(data) in kemampuanBicara">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" v-model="input[data.model]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="column is-12">

                                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Koping Mekanisme
                                                    </h1>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-4" v-for="(data) in koping">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" v-model="input[data.model]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Support Sistem</h1>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-4" v-for="(data) in support">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" v-model="input[data.model]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="column is-8">
                                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Merasa dikucilkan
                                                    </h1>
                                                    <div class="columns is-multiline">
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox v-model="input.merasaDikucilkan" true-value="Ya"
                                                                    label="Ya" color="primary" square />
                                                            </VControl>
                                                        </VField>
                                                        <VField>
                                                            <VControl>
                                                                <VCheckbox v-model="input.merasaDikucilkan"
                                                                    true-value="Tidak" label="Tidak" color="primary"
                                                                    square />
                                                            </VControl>

                                                        </VField>
                                                    </div>

                                                </div>
                                                <div class="column is-3">
                                                    <h1 style="font-weight: bold; margin-bottom: 1rem;"> Kepercayaan pada
                                                        Penyakit</h1>
                                                    <VField addons>
                                                        <VControl expanded>
                                                            <VInput type="text" class="input"
                                                                placeholder="Kepercayaan pada Penyakit"
                                                                v-model="input.kepercayaanPadaPenyakit" />
                                                        </VControl>

                                                    </VField>
                                                </div>

                                            </Fieldset>
                                        </div>

                                        <div class="column is-12">
                                            <Fieldset class="p-fieldsets" legend="Data Spiritual" :toggleable="true">
                                                <div class="columns is-multiline">
                                                    <div class="column is-10">
                                                        <h1 style="font-weight: bold; margin-bottom: 1rem;"> Agama </h1>
                                                        <div class="columns is-multiline">
                                                            <div class="column is-2" v-for="(data) in agama">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="p-0" v-model="input[data.model]"
                                                                            :true-value="data.title" :label="data.title"
                                                                            color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="column is-2"
                                                        style="margin-left: -9rem; margin-top: 1.7rem;">
                                                        <VControl expanded>
                                                            <VInput type="text" class="input" placeholder="Agama Lainnya"
                                                                v-model="input.agamaLainnya" />
                                                        </VControl>
                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 1rem;"> Perubahan pola
                                                        ibadah setelah sakit
                                                    </h1>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-3" v-for="(data) in polaIbadah">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" v-model="input[data.model]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="column is-12">
                                                    <h1 style="font-weight: bold; margin-bottom: 1rem;"> Respon akibat sakit
                                                    </h1>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-3" v-for="(data) in Respon">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" v-model="input[data.model]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </Fieldset>
                                        </div>
                                        <div class="column is-12">
                                            <Fieldset class="p-fieldsets" legend="Data Spiritual" :toggleable="true">
                                                <div class="columns is-multiline">
                                                    <table align="center" class="triase"
                                                        style="width: 90%; margin-top: 2rem;">
                                                        <tr>
                                                            <th colspan="4" style="text-align : center"> Tindakan </th>
                                                        </tr>
                                                        <tr>
                                                            <th style="text-align : center"> No </th>
                                                            <th style="text-align : center"> Kriteria Pasien </th>
                                                            <th style="text-align: center"> Pilihan </th>
                                                            <th style="text-align: center"> Keterangan </th>
                                                        </tr>
                                                        <tr v-for="(datas) in planning">
                                                            <td>{{ datas.no }}</td>
                                                            <td>{{ datas.Kriteria }}</td>
                                                            <td>
                                                                <div class="columns is-multiline">
                                                                    <div class="column is-12">
                                                                        <VField v-for="(check) in datas.pilihan">
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0"
                                                                                    v-model="input[check.model]"
                                                                                    :true-value="check.label"
                                                                                    :label="check.label" color="primary"
                                                                                    square />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <VField>
                                                                    <VControl>
                                                                        <VTextarea rows="2"
                                                                            v-model="input[datas.keterangan]"
                                                                            placeholder="Your message..." />
                                                                    </VControl>
                                                                </VField>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                </div>
                                            </Fieldset>
                                        </div>

                                        <div class="column is-12">
                                            <Fieldset class="p-fieldsets" legend="Kebutuhan Edukasi" :toggleable="true">
                                                <div class="columns is-multiline">
                                                    <div class="column is-12">
                                                        <h1 style="font-weight: bold;"> Kebutuhan Informasi dan Edukasi
                                                        </h1>
                                                    </div>
                                                    <div class="column is-9">
                                                        <h1 style="font-weight: bold; margin-bottom: 1rem;"> Bicara</h1>

                                                        <div class="columns is-multiline">
                                                            <div class="column is-3" v-for="(data) in kebutuhanBicara">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="p-0" v-model="input[data.model]"
                                                                            :true-value="data.title" :label="data.title"
                                                                            color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-4" style="margin-top:-1rem;">
                                                                <Vfield>
                                                                    <VControl>
                                                                        <VInput type="text" v-model="input.keteranganBicara"
                                                                            placeholder="Keterangan Bicara" />
                                                                    </VControl>
                                                                </Vfield>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-9">
                                                        <h1 style="font-weight: bold; margin-bottom: 1rem;"> Penerjemah
                                                        </h1>
                                                        <div class="columns is-multiline">
                                                            <div class="column is-3" v-for="(data) in penerjemah">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="p-0" v-model="input[data.model]"
                                                                            :true-value="data.title" :label="data.title"
                                                                            color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-4" style="margin-top:-1rem;">
                                                                <Vfield>
                                                                    <VControl>
                                                                        <VInput type="text"
                                                                            v-model="input.keteranganPenerjemah"
                                                                            placeholder="Keterangan Penerjemah" />
                                                                    </VControl>
                                                                </Vfield>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="column is-9">
                                                        <h1 style="font-weight: bold; margin-bottom: 1rem;"> Bahasa Isyarat
                                                        </h1>
                                                        <div class="columns is-multiline">
                                                            <div class="column is-3" v-for="(data) in bahasaIsyarat">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="p-0" v-model="input[data.model]"
                                                                            :true-value="data.title" :label="data.title"
                                                                            color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-4" style="margin-top:-1rem;">
                                                                <Vfield>
                                                                    <VControl>
                                                                        <VInput type="text" v-model="input.keteranganBahasa"
                                                                            placeholder="Keterangan Bahasa Isyarat" />
                                                                    </VControl>
                                                                </Vfield>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="column is-8">
                                                        <h1 style="font-weight: bold; margin-bottom: 1rem;"> Tingkat
                                                            Pendidikan</h1>
                                                        <div class="columns is-multiline">
                                                            <div class="column is-3" v-for="(data) in pendidikan">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="p-0" v-model="input[data.model]"
                                                                            :true-value="data.title" :label="data.title"
                                                                            color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="column is-12">
                                                        <h1 style="font-weight: bold; margin-bottom: 1rem;"> Kebutuhan
                                                            Edukasi
                                                        </h1>
                                                        <div class="columns is-multiline">
                                                            <div class="column is-4" v-for="(data) in kebutuhanEdukasi">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="p-0" v-model="input[data.model]"
                                                                            :true-value="data.title" :label="data.title"
                                                                            color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-4" style="margin-top:-1rem;">
                                                                <Vfield>
                                                                    <VControl>
                                                                        <VInput type="text"
                                                                            v-model="input.keteranganEdukasi"
                                                                            placeholder="Keterangan Edukasi" />
                                                                    </VControl>
                                                                </Vfield>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </Fieldset>
                                        </div>
                                        <div class="column is-12">
                                            <table align="center" class="triase" style="width: 90%">
                                                <tr>
                                                    <th style="text-align : center"> MASALAH KEPERAWATAN </th>
                                                    <th style="text-align: center"> IMPLEMENTASI </th>

                                                </tr>
                                                <tr>
                                                    <td>
                                                <tr>
                                                    <div class="column is-multiline pl-2">
                                                        <div class="column is-12" v-for="(data, i) in listMasalah">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model + i]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        class="p-0" color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </tr>

                                                </td>
                                                <td>
                                                    <tr>
                                                        <div class="column is-multiline pl-2">
                                                            <div class="column is-12" v-for="(data, i) in listImplementasi">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.model + i]"
                                                                            :true-value="data.nama" :label="data.nama"
                                                                            class="p-0" color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </tr>
                                                    <tr>
                                                        <div class="flex">
                                                            <p style="padding-right: 2rem;">Pemberian Oksigen :</p>
                                                            <VField addons style="margin-top:-1rem">
                                                                <VControl expanded>
                                                                    <VInput type="text" class="input"
                                                                        placeholder="Pemberian O2"
                                                                        v-model="input.pemberianO2" />
                                                                </VControl>
                                                                <VControl class="field-addon-body">
                                                                    <VButton static>lt/mnt</VButton>
                                                                </VControl>
                                                            </VField>
                                                        </div>

                                                    </tr>
                                                </td>

                                                </tr>
                                                <tr>
                                                    <td>
                                                <tr>
                                                    <div class="column is-multiline pl-3">
                                                        <div class="column is-12" v-for="(data, i) in listMasalah2">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model + i]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        class="p-0" color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </tr>
                                                </td>
                                                <td>
                                                    <tr>
                                                        <div class="column is-multiline pl-3">
                                                            <div class="column is-12" v-for="(data, i) in listPemberian">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.detail + i]"
                                                                            :true-value="data.nama" :label="data.nama"
                                                                            class="p-0" color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </tr>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                <tr>
                                                    <div class="column is-multiline pl-3">
                                                        <div class="column is-12" v-for="(data, i) in listMasalah3">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model + i]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        class="p-0" color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </tr>
                                                </td>
                                                <td>
                                                    <tr>
                                                        <div class="column is-multiline pl-3">
                                                            <div class="column is-12"
                                                                v-for="(data, i) in listImplementasi2">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.cek + i]"
                                                                            :true-value="data.nama" :label="data.nama"
                                                                            class="p-0" color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>


                                                    </tr>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                <tr>
                                                    <div class="column is-multiline pl-3">
                                                        <div class="column is-12" v-for="(data, i) in listMasalah4">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model + i]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        class="p-0" color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </tr>
                                                </td>
                                                <td>
                                                    <tr>
                                                        <div class="column is-multiline pl-3">
                                                            <div class="column is-12"
                                                                v-for="(data, i) in listImplementasi3">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.label + i]"
                                                                            :true-value="data.nama" :label="data.nama"
                                                                            class="p-0" color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>

                                                    </tr>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                <tr>
                                                    <div class="column is-multiline pl-3">
                                                        <div class="column is-12" v-for="(data, i) in listMasalah5">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model + i]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        class="p-0" color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>

                                                </tr>
                                                </td>
                                                <td>
                                                    <tr>
                                                        <div class="column is-multiline pl-3">
                                                            <div class="column is-12"
                                                                v-for="(data, i) in listImplementasi4">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.model + i]"
                                                                            :true-value="data.nama" :label="data.nama"
                                                                            class="p-0" color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>

                                                    </tr>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                <tr>
                                                    <div class="column is-multiline pl-3">
                                                        <div class="column is-12" v-for="(data, i) in listMasalah6">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model + i]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        class="p-0" color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </tr>
                                                </td>
                                                <td>
                                                    <tr>
                                                        <div class="column is-multiline pl-3">
                                                            <div class="column is-12"
                                                                v-for="(data, i) in listImplementasi5">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.model + i]"
                                                                            :true-value="data.nama" :label="data.nama"
                                                                            class="p-0" color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </tr>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                <tr>
                                                    <div class="column is-multiline pl-3">
                                                        <div class="column is-12" v-for="(data, i) in listMasalah7">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model + i]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        class="p-0" color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>

                                                </tr>
                                                </td>
                                                <td>
                                                    <tr>
                                                        <div class="column is-multiline pl-3">
                                                            <div class="column is-12"
                                                                v-for="(data, i) in listImplementasi6">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.model + i]"
                                                                            :true-value="data.nama" :label="data.nama"
                                                                            class="p-0" color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </tr>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                <tr>
                                                    <div class="column is-multiline pl-3">
                                                        <div class="column is-12" v-for="(data, i) in listMasalah8">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data.model + i]"
                                                                        :true-value="data.title" :label="data.title"
                                                                        class="p-0" color="primary" square />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </tr>
                                                </td>
                                                <td>
                                                    <tr>
                                                        <div class="column is-multiline pl-3">
                                                            <div class="column is-12"
                                                                v-for="(data, i) in listImplementasi7">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="input[data.model + i]"
                                                                            :true-value="data.nama" :label="data.nama"
                                                                            class="p-0" color="primary" square />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </tr>

                                                </td>
                                                </tr>


                                            </table>
                                        </div>
                                    </div>
                                    <div class="column is-6" style="margin-left: auto;">
                                        <VCard class="border-card pink">
                                            <div class="columns is-multiline">
                                            <div class="column is-6">
                                                    <h1 class="emr">Perawat</h1>
                                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="feather:search">
                                                            <AutoComplete v-model="input.perawat" :suggestions="d_Pegawai"
                                                                @complete="fetchPegawai($event)" :optionLabel="'label'"
                                                                :dropdown="true" :minLength="3" :appendTo="'body'"
                                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                placeholder="Cari Perawat" />
                                                        </VControl>
                                                    </VField>
                                                </div>
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
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import * as EMR from '../page-emr-plugins/pengkajian-keperawatan-rj'

useHead({
    title: 'Pengkajian Keperawatan Pasien Rawat Jalan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let caraBayar = ref(EMR.caraBayar())
let tindakLanjut = ref(EMR.tindakLanjut())
let Pilihan = ref(EMR.Pilihan())
let pegangKursisaatDuduk = ref(EMR.pegangKursisaatDuduk())
let hasilResikoJatuh = ref(EMR.hasilResikoJatuh())
let ListStatus = ref(EMR.ListStatus())
let ekpresiMuka = ref(EMR.ekpresiMuka())
let kemampuanBicara = ref(EMR.kemampuanBicara())
let koping = ref(EMR.koping())
let agama = ref(EMR.agama())
let polaIbadah = ref(EMR.polaIbadah())
let Respon = ref(EMR.Respon())
let support = ref(EMR.support())
let kebutuhanBicara = ref(EMR.kebutuhanBicara())
let penerjemah = ref(EMR.penerjemah())
let pendidikan = ref(EMR.pendidikan())
let kebutuhanEdukasi = ref(EMR.kebutuhanEdukasi())
let bahasaIsyarat = ref(EMR.bahasaIsyarat())
let planning = ref(EMR.planning())
let listMasalah = ref(EMR.listMasalah())
let listMasalah2 = ref(EMR.listMasalah2())
let listMasalah3 = ref(EMR.listMasalah3())
let listMasalah4 = ref(EMR.listMasalah4())
let listMasalah5 = ref(EMR.listMasalah5())
let listMasalah6 = ref(EMR.listMasalah6())
let listMasalah7 = ref(EMR.listMasalah7())
let listMasalah8 = ref(EMR.listMasalah8())
let listImplementasi = ref(EMR.listImplementasi())
let listPemberian = ref(EMR.listPemberian())
let listImplementasi4 = ref(EMR.listImplementasi4())
let listImplementasi2 = ref(EMR.listImplementasi2())
let listImplementasi3 = ref(EMR.listImplementasi3())
let listImplementasi5 = ref(EMR.listImplementasi5())
let listImplementasi6 = ref(EMR.listImplementasi6())
let listImplementasi7 = ref(EMR.listImplementasi7())



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
// console.log(props.registrasi)
const pasien: any = ref({})
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

const COLLECTION: any = ref('PengkajianKeperawatanPasienRJ') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: new Date()
})
const d_Pegawai: any = ref([])
const isAktive = ref()
const router = useRouter()
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})

let listImageNyeri = ref(
    {
        "nama": "Hurts", "detail": [
            {
                "nama": "No Hurt", "descNilai": 0,
                "img": "/images/skalanyeri/1.png"
            },
            {
                "nama": "Hurts Little Bit", "descNilai": 2,
                "img": "/images/skalanyeri/2.png",
            },
            {
                "nama": "Hurts Little More", "descNilai": 4,
                "img": "/images/skalanyeri/3.png",
            },
            {
                "nama": "Hurts Even More", "descNilai": 6,
                "img": "/images/skalanyeri/4.png",
            },
            {
                "nama": "Hurts Whole Lot", "descNilai": 8,
                "img": "/images/skalanyeri/5.png",
            },
            {
                "nama": "Hurts whorts", "descNilai": 10,
                "img": "/images/skalanyeri/6.png",
            }]
    }
)
let listSkoringNyeri: any = ref(
    {
        "nama": "Score ", "detail": [
            { "nama": "0 - 1 = Tidak Ada Nyeri", "descNilai": 0 },
            { "nama": "2 - 3 = Sedikit Nyeri", "descNilai": 2 },
            { "nama": "4 - 5 = Cukup Nyeri", "descNilai": 4 },
            { "nama": "6 - 7 = Lumayan Nyeri", "descNilai": 6 },
            { "nama": "8 - 9 = Sangat Nyeri", "descNilai": 8 },
            { "nama": "10 = Amat Sangat Nyeri", "descNilai": 10 },
        ]
    }
)
const isLoading = ref(false)
const listRiwayat = ref([])
const loadRiwayat = () => {

    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (NOREC_EMRPASIEN.value == '') {
                    ``
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}

const simpan = () => {
    let object: any = {}
    let ID = input.value.id ? input.value.id : ''

    object = input.value //set inputan
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
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            NOREC_EMRPASIEN.value = response.norec_emr

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
const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}
const test = (e: any, i: any) => {

    let listSkor = listSkoringNyeri.value.detail
    let listImage = listImageNyeri.value.detail


    listSkor.forEach((element: any) => {
        if (element.descNilai == e.descNilai) {
            input.value.skor = e.descNilai
        }
    });
    isAktive.value = i

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
fetchPasien()
loadRiwayat()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

.p-fieldsets .p-fieldset-content {
    background: #ffffff;
}

.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}

table.triase {
    border-collapse: collapse;
    width: 100%;
}

table.triase,
.triase th,
.triase td {
    border: 0.5px solid grey;
}


.triase th,
.triase td {
    padding: 8px;
    vertical-align: middle !important;
}</style>
