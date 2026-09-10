<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>FORMULIR KAJIAN RIWAYAT MENYUSUI</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12">
                    <div class="columns is-multiline ">
                        <div class="column is-12">
                            <h1 style="font-weight: bold;">CATATAN MEDIK
                            </h1>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.layananperawatan"
                                        true-value="ANC" label="Antenatal Care" color="primary" circle />
                                </VControl>
                            </VField>
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.layananperawatan"
                                        true-value="PNC" label="Post Natal Care" color="primary" circle />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField vertical label="Kunjungan Tanggal Pertama">
                                <VControl>
                                    <VDatePicker v-model="input.tanggalpertama" mode="date" trim-weeks
                                        :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" placeholder="Tanggal"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VControl>
                            </VField>
                            <VField vertical label="No. RM">
                                <VControl>
                                    <VInput type="text" class="input" v-model.number="input.norm" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <h1 style="font-weight: bold;">IDENTITAS SUAMI
                            </h1>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Nama Suami
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.namaayah" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Umur Suami
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.umurayah" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Pendidikan Suami
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.pendidikanayah" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Agama Suami
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.agamaayah" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Pekerjaan Suami
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.pekerjaanayah" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <h1 style="font-weight: bold;">CARA KUNJUNGAN
                            </h1>
                        </div>
                        <div class="column is-6" v-for="kunjunganChunked in chunkData(allData.d_kunjungan, 3)">
                            <VField v-for="(kunjunganData, kunjunganIndex) in kunjunganChunked" :key="kunjunganIndex">
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" :true-value="kunjunganData.value"
                                        :value="kunjunganData"
                                        @change.stop="handlerCheckbox(kunjunganData, 'd_kunjungan')"
                                        v-model="input.kunjungan" :label="kunjunganData.label" color="primary" circle />
                                    <VInput type="text" class="input" placeholder="Ketik" v-model="input.ketkunjungan"
                                        v-if="kunjunganData.isChecked && !kunjunganData.isHiddenForm" />
                                </VControl>
                            </VField>
                        </div>

                        <!-- <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kunjungan" true-value="ASI"
                                        label="Dikirim dari Kader ASI" color="primary" circle />
                                    <VInput type="text" class="input" placeholder="Ketik" v-model="input.ketkunjungan"
                                        v-if="input.kunjungan == 'ASI'" />
                                </VControl>
                            </VField>
                        </div> -->
                    </div>
                </div>

                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline ">
                        <div class="column is-12">
                            <h1 style="font-weight: bold;">KELUHAN UTAMA
                            </h1>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.keluhanutama" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 mt-auto mb-auto">
                            <h1 style="font-weight: bold;">
                                RIWAYAT KEHAMILAN, PERSALINAN DAN LAKTASI SEBELUMNYA
                            </h1>
                        </div>
                        <div class="column is-4">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>G</VButton>
                                </VControl>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="G"
                                        v-model.number="input.riwayathamilg" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>P</VButton>
                                </VControl>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="P"
                                        v-model.number="input.riwayathamilp" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField addons>
                                <VControl class="field-addon-body">
                                    <VButton static>A</VButton>
                                </VControl>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="A"
                                        v-model.number="input.riwayathamila" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <div class="column" style="overflow:auto">
                                    <table class="table-hiet">
                                        <thead>
                                            <tr>
                                                <th class="th-hie" width="10%">Anak Ke</th>
                                                <th class="th-hie" width="20%">Jenis Kelamin</th>
                                                <th class="th-hie">Umur / Tanggal Lahir</th>
                                                <th class="th-hie">Menyusui Ekslusif</th>
                                                <th class="th-hie">Umur Disapih</th>
                                                <th class="th-hie">Masalah dalam Menyusui</th>
                                                <th class="th-hie" width="5%">#</th>
                                            </tr>
                                        </thead>
                                        <tbody v-for="(item, index) in input.riwayatanak" :key="index">
                                            <tr>
                                                <td class="td-hie">
                                                    <VField>
                                                        <VControl>
                                                            <VInput type="text" placeholder="" :value="index"
                                                                class="is-rounded_Z" v-model="item.urutananak" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td class="td-hie">
                                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                                        <VControl>
                                                            <Multiselect v-model="item.jeniskelamin" :attrs="{ value }"
                                                                placeholder="--Pilih--" label="label"
                                                                :options="d_jeniskelamin" :searchable="true"
                                                                track-by="label" mode="single" autocomplete="off">
                                                            </Multiselect>
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td class="td-hie">
                                                    <VDatePicker v-model="item.tgllahir" color="green" trim-weeks
                                                        mode="datetime" :max-date="new Date()">
                                                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                                                            <VField>
                                                                <VControl icon="feather:calendar">
                                                                    <VInput type="text" placeholder="Select a date"
                                                                        :value="inputValue" v-on="inputEvents"
                                                                        class="is-rounded_Z" />
                                                                </VControl>
                                                            </VField>
                                                        </template>
                                                    </VDatePicker>
                                                </td>
                                                <td class="td-hie">
                                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                                        <VControl>
                                                            <Multiselect v-model="item.isekslusif" :attrs="{ value }"
                                                                placeholder="--Pilih--" label="label"
                                                                :options="d_ekslusif" :searchable="true"
                                                                track-by="label" mode="single" autocomplete="off">
                                                            </Multiselect>
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td class="td-hie">
                                                    <VField>
                                                        <VControl>
                                                            <VInput type="text" placeholder="" class="is-rounded_Z"
                                                                v-model="item.umurdisapih" />
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td class="td-hie">
                                                    <VField>
                                                        <VControl>
                                                            <VTextarea v-model="item.masalahmenyusui" rows="2">
                                                            </VTextarea>
                                                        </VControl>
                                                    </VField>
                                                </td>
                                                <td class="td-hie">
                                                    <div class="column" style="text-align: center;">
                                                        <VIconButton type="button" raised circle icon="feather:plus"
                                                            @click="addNewItem('riwayatanak')" color="info"
                                                            v-tooltip.bubble="'Tambah '">
                                                        </VIconButton>
                                                        <VIconButton class="mt-1" v-if="index > 0" type="button" raised
                                                            circle icon="feather:trash"
                                                            @click="removeItem(index, 'riwayatanak')" color="danger">
                                                        </VIconButton>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 mb-3">
                            <h1 style="font-weight: bold;">
                                KEHAMILAN DAN PERSALINAN TERAKHIR
                            </h1>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Kehamilan ini
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="(kehamilanData, kehamilanIndex) in allData.d_kehamilan"
                                            :key="kehamilanIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.keinginanhamil"
                                                        @change.stop="handlerCheckbox(kehamilanData, 'd_kehamilan')"
                                                        :true-value="kehamilanData.value" :label="kehamilanData.label"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Usaha pengguguran
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="(pengguguranData, pengguguranIndex) in allData.d_pengguguran"
                                            :key="pengguguranIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.usahapenguguran"
                                                        @change.stop="handlerCheckbox(pengguguranData, 'd_pengguguran')"
                                                        :true-value="pengguguranData.value"
                                                        :label="pengguguranData.label" color="primary" circle />
                                                    <VInput type="text" class="input" placeholder="Ketik"
                                                        v-model="input.ketusahapenguguran"
                                                        v-if="pengguguranData.isChecked && !pengguguranData.isHiddenForm" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Keadaan sekarang
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="(sudahlahirData, sudahLahirIndex) in allData.d_keadaan"
                                            :key="sudahLahirIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.keadaan"
                                                        @change.stop="handlerCheckbox(sudahlahirData, 'd_keadaan')"
                                                        :true-value="sudahlahirData.value" :label="sudahlahirData.label"
                                                        color="primary" circle />
                                                    <template
                                                        v-if="sudahlahirData.isChecked && !sudahlahirData.isHiddenForm">
                                                        <VInput type="text" class="input" placeholder="Ketik"
                                                            v-model="input.ketkeadaan"
                                                            v-if="sudahlahirData.formType != undefined && sudahlahirData.formType == 'text'" />
                                                        <VDatePicker v-model="input.ketkeadaan" color="green" trim-weeks
                                                            v-else-if="sudahlahirData.formType != undefined && sudahlahirData.formType == 'date'"
                                                            mode="datetime" :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }"
                                                                class="pb-0">
                                                                <VField>
                                                                    <VControl icon="feather:calendar">
                                                                        <VInput type="text" placeholder="Select a date"
                                                                            :value="inputValue" v-on="inputEvents"
                                                                            class="is-rounded_Z" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>

                                                    </template>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        ANC
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6" v-for="(isancData, isancIndex) in allData.d_isanc"
                                            :key="isancIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isanc"
                                                        @change.stop="handlerCheckbox(isancData, 'd_isanc')"
                                                        :true-value="isancData.value" :label="isancData.label"
                                                        color="primary" circle />
                                                    <VInput type="text" class="input" placeholder="Ketik"
                                                        v-model="input.ketisanc"
                                                        v-if="!isancData.isHiddenForm && isancData.isChecked" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Penyakit Ibu
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.penyakitibu" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Penyakit Kehamilan
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.penyakitkehamilan" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Melakukan Perawatan
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="(isperawatanData, isperawatanIndex) in allData.d_isperawatan"
                                            :key="isperawatanIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.isperawatan"
                                                        @change.stop="handlerCheckbox(isperawatanData, 'd_iskeperawatan')"
                                                        :true-value="isperawatanData.value"
                                                        :label="isperawatanData.label" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Puting Susu
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.putingsusu" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Kelainan Payudara
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="(kelainanpayudaraData, isperawatanIndex) in allData.d_kelainanpayudara"
                                            :key="isperawatanIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.kelainanpayudara"
                                                        @change.stop="handlerCheckbox(kelainanpayudaraData, 'd_kelainanpayudara')"
                                                        :true-value="kelainanpayudaraData.value"
                                                        :label="kelainanpayudaraData.label" color="primary" circle />
                                                    <VInput type="text" class="input" placeholder="Ketik"
                                                        v-model="input.ketkelainanpayudara"
                                                        v-if="!kelainanpayudaraData.isHiddenForm && kelainanpayudaraData.isChecked" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Obat Selama Hamil
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.obathamil" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Jamu Selama Hamil
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.jamuhamil" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Obat Untuk Kelancaran Menyusui
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.obatkelancaranmenyusui" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Jamu Untuk Kelancaran Menyusui
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.jamukelancaranmenyusui" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Tempat Persalinan
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="tempatsalinChunked in chunkData(allData.d_tempatsalin, 2)">
                                            <VField v-for="(tempatsalinData, tempatsalinIndex) in tempatsalinChunked"
                                                :key="tempatsalinIndex">
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.tempatsalin"
                                                        @change.stop="handlerCheckbox(tempatsalinData, 'd_tempatsalin')"
                                                        :true-value="tempatsalinData.value"
                                                        :label="tempatsalinData.label" color="primary" circle />
                                                    <VInput type="text" class="input" placeholder="Ketik"
                                                        v-model="input.kettempatsalin"
                                                        v-if="!tempatsalinData.isHiddenForm && tempatsalinData.isChecked" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Cara Lahir
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-4"
                                            v-for="caralahirChunked in chunkData(allData.d_caralahir, 2)">
                                            <VField v-for="(caralahirData, caralahirIndex) in caralahirChunked"
                                                :key="caralahirIndex">
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.caralahir"
                                                        @change.stop="handlerCheckbox(caralahirData, 'd_caralahir')"
                                                        :true-value="caralahirData.value" :label="caralahirData.label"
                                                        color="primary" circle />
                                                    <VInput type="text" class="input" placeholder="Ketik"
                                                        v-model="input.ketcaralahir"
                                                        v-if="!caralahirData.isHiddenForm && caralahirData.isChecked" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Keadaan Bayi
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.keadaanbayi" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Keadaan APGAR
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-12" v-for="(apgarData, apgarIndex) in allData.d_apgar"
                                            :key="apgarIndex">
                                            <VField vertical>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.apgar"
                                                        @change.stop="handlerCheckbox(apgarData, 'd_apgar')"
                                                        :true-value="apgarData.value" :label="apgarData.label"
                                                        color="primary" circle />

                                                    <VField horizontal addons
                                                        v-if="!apgarData.isHiddenForm && apgarData.isChecked">
                                                        <VField addons class="mr-5">
                                                            <VControl class="field-addon-body">
                                                                <VButton static>1 Menit</VButton>
                                                            </VControl>
                                                            <VControl expanded>
                                                                <VInput type="text" class="input" placeholder="Ketik"
                                                                    v-model="input.ketapgarsatu" />
                                                            </VControl>
                                                        </VField>
                                                        <VField addons>
                                                            <VControl class="field-addon-body">
                                                                <VButton static>5 Menit</VButton>
                                                            </VControl>
                                                            <VControl expanded>
                                                                <VInput type="text" class="input" placeholder="Ketik"
                                                                    v-model="input.ketapgarlima" />
                                                            </VControl>
                                                        </VField>
                                                    </VField>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Jumlah Bayi
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="(jumlahbayiData, jumlahbayiIndex) in allData.d_jumlahbayi"
                                            :key="jumlahbayiIndex">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.jumlahbayi"
                                                        @change.stop="handlerCheckbox(jumlahbayiData, 'd_jumlahbayi')"
                                                        :true-value="jumlahbayiData.value" :label="jumlahbayiData.label"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mt-auto mb-auto">
                                    <h3>
                                        Berat Lahir
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.beratlahir" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>gram</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mt-auto mb-auto">
                                    <h3>
                                        Panjang Lahir
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.panjang" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>cm</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Kelainan Bawaan
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.kelainanbawaan" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        IMD
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6" v-for="(imdData, imdIndex) in allData.d_imd"
                                            :key="imdIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.imd"
                                                        @change.stop="handlerCheckbox(imdData, 'd_imd')"
                                                        :true-value="imdData.value" :label="imdData.label"
                                                        color="primary" circle />
                                                    <VInput type="text" class="input" placeholder="Ketik"
                                                        v-model="input.ketimd"
                                                        v-if="!imdData.isHiddenForm && imdData.isChecked" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Rawat Gabung
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="(rawatgabungData, rawatgabungIndex) in allData.d_rawatgabung"
                                            :key="rawatgabungIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.rawatgabung"
                                                        @change.stop="handlerCheckbox(rawatgabungData, 'd_rawatgabung')"
                                                        :true-value="rawatgabungData.value"
                                                        :label="rawatgabungData.label" color="primary" circle />
                                                    <VInput type="text" class="input" placeholder="Ketik"
                                                        v-model="input.ketrawatgabung"
                                                        v-if="!rawatgabungData.isHiddenForm && rawatgabungData.isChecked" />
                                                </VControl>
                                            </VField>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mt-auto mb-auto">
                                    <h3>
                                        Mulai Menyusui
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput type="text" class="input" placeholder=""
                                                v-model="input.mulaimenyusui" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static style="width: auto !important">Jam setelah lahir</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Minuman Bayi
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.minumanbayi" true-value="1" label="Susu Formula"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.minumanbayi" true-value="2" label="Air Gula"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Selain ASI
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="(selainasiData, selainasiIndex) in allData.d_selainasi"
                                            :key="selainasiIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.selainasi"
                                                        @change.stop="handlerCheckbox(selainasiData, 'd_selainasi')"
                                                        :true-value="selainasiData.value" :label="selainasiData.label"
                                                        color="primary" circle />
                                                    <VInput type="text" class="input" placeholder="Ketik"
                                                        v-model="input.ketselainasi"
                                                        v-if="!selainasiData.isHiddenForm && selainasiData.isChecked" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <h1 style="font-weight: bold;">
                                PEMERIKSAAN FISIK
                            </h1>
                        </div>
                        <div class="column is-12 mt-5 mb-4">
                            <h3 style="font-weight: bold;">
                                IBU
                            </h3>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Keadaan Umum
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.keadaanumumibu" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Berat/Tinggi Badan
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField horizontal addons>
                                        <VField addons class="mr-5">
                                            <VControl expanded>
                                                <VInput type="text" class="input" placeholder="Ketik"
                                                    v-model="input.beratibu" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>Kg/</VButton>
                                            </VControl>
                                        </VField>
                                        <VField addons>
                                            <VControl expanded>
                                                <VInput type="text" class="input" placeholder="Ketik"
                                                    v-model="input.tinggiibu" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>cm</VButton>
                                            </VControl>
                                        </VField>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Psikis Kesan
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.psikiskesanibu" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Keadaan Payudara
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.keadaanpayudaraibu" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Kelainan Payudara
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.kelainanpayudaraibu" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Kesulitan Menyusui
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="(kesulitanmenyusuiData, kesulitanmenyusuiIndex) in allData.d_kesulitanmenyusui"
                                            :key="kesulitanmenyusuiIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.kesulitanmenyusui"
                                                        @change.stop="handlerCheckbox(kesulitanmenyusuiData, 'd_kesulitanmenyusui')"
                                                        :true-value="kesulitanmenyusuiData.value"
                                                        :label="kesulitanmenyusuiData.label" color="primary" circle />
                                                    <VInput type="text" class="input" placeholder="Sebab"
                                                        v-model="input.ketkesulitanmenyusui"
                                                        v-if="!kesulitanmenyusuiData.isHiddenForm && kesulitanmenyusuiData.isChecked" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 mt-5 mb-4">
                            <h3 style="font-weight: bold;">
                                Bayi
                            </h3>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Jenis Kelamin
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField class="is-autocomplete-select" v-slot="{ id }">
                                        <VControl>
                                            <Multiselect v-model="input.jeniskelaminbayi" :attrs="{ value }"
                                                placeholder="--Pilih--" label="label" :options="d_jeniskelamin"
                                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Umur
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField horizontal addons>
                                        <VField addons class="mr-5">
                                            <VControl expanded>
                                                <VInput type="text" class="input" placeholder="Ketik"
                                                    v-model="input.bulanumurbayi" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>bulan</VButton>
                                            </VControl>
                                        </VField>
                                        <VField addons>
                                            <VControl expanded>
                                                <VInput type="text" class="input" placeholder="Ketik"
                                                    v-model="input.hariumurbayi" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>hari</VButton>
                                            </VControl>
                                        </VField>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3 mb-auto mt-auto">
                                    <h3>
                                        Keadaan Umum
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.keadaanumumbayi" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Refleks Menghisap
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="(reflekshisapbayiData, reflekshisapbayiIndex) in allData.d_reflekshisapbayi"
                                            :key="reflekshisapbayiIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.reflekshisapbayi"
                                                        @change.stop="handlerCheckbox(reflekshisapbayiData, 'd_reflekshisapbayi')"
                                                        :true-value="reflekshisapbayiData.value"
                                                        :label="reflekshisapbayiData.label" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Refleks Menelan
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="(refleksnelanbayi, refleksnelanbayiIndex) in allData.d_refleksnelanbayi"
                                            :key="refleksnelanbayiIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.refleksnelanbayi"
                                                        @change.stop="handlerCheckbox(refleksnelanbayi, 'd_refleksnelanbayi')"
                                                        :true-value="refleksnelanbayi.value"
                                                        :label="refleksnelanbayi.label" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Kelainan
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6"
                                            v-for="(kelainanbayiData, kelainanbayiIndex) in allData.d_kelainanbayi"
                                            :key="kelainanbayiIndex">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.kelainanbayi[kelainanbayiIndex]"
                                                        @change.stop="handlerCheckbox(kelainanbayiData, 'd_kelainanbayi')"
                                                        :true-value="kelainanbayiData.value"
                                                        :label="kelainanbayiData.label" color="primary" circle />
                                                    <VInput type="text" class="input" placeholder="Sebab"
                                                        v-model="input.ketkelainanbayi"
                                                        v-if="!kelainanbayiData.isHiddenForm && kelainanbayiData.isChecked" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 mt-5 mb-4">
                            <h3 style="font-weight: bold;">
                                OBSERVASI TEKNIK MENYUSUI
                            </h3>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <h3>
                                        Teknik Menyusui
                                    </h3>
                                </div>
                                <div class="column is-9">
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.teknikmenyusuiibu" true-value="1" label="Baik"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.teknikmenyusuiibu" true-value="2" label="Kurang"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h3>
                                        Catatan (Jelaskan kekurangannya)
                                    </h3>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.catatan" rows="4">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 mt-5 mb-4">
                            <h3 style="font-weight: bold;">
                                RENCANA TINDAK LANJUT
                            </h3>
                        </div>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <!-- <div class="column is-6">
                                            <VField horizontal>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                        v-model="input.kelainanbayi"
                                                        @change.stop="handlerCheckbox(kelainanbayiData, 'd_kelainanbayi')"
                                                        :true-value="kelainanbayiData.value"
                                                        :label="kelainanbayiData.label" color="primary" circle />
                                                    <VInput type="text" class="input" placeholder="Sebab"
                                                        v-model="input.ketkelainanbayi"
                                                        v-if="!kelainanbayiData.isHiddenForm && kelainanbayiData.isChecked" />
                                                </VControl>
                                            </VField>
                                        </div> -->
                                    <VField horizontal
                                        v-for="(rencanatindakData, rencanatindakIndex) in allData.d_rencanatindak"
                                        :key="rencanatindakIndex">
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.rencanatindak"
                                                @change.stop="handlerCheckbox(rencanatindakData, 'd_rencanatindak')"
                                                :true-value="rencanatindakData.value" :label="rencanatindakData.label"
                                                color="primary" circle />
                                        </VControl>
                                        <VControl class="ml-5" style="width: 70%"
                                            v-if="!rencanatindakData.isHiddenForm && rencanatindakData.isChecked">
                                            <VInput type="text" class="input" placeholder="Ketik"
                                                v-model="input.ketrencanatindak"
                                                v-if="rencanatindakData.formType != undefined && rencanatindakData.formType == 'text'" />
                                            <VDatePicker v-model="input.ketrencanatindak" color="green" trim-weeks
                                                mode="datetime" :max-date="new Date()"
                                                v-else-if="rencanatindakData.formType != undefined && rencanatindakData.formType == 'date'">
                                                <template #default="{ inputValue, inputEvents }" class="pb-0">
                                                    <VField>
                                                        <VControl icon="feather:calendar">
                                                            <VInput type="text" placeholder="Select a date"
                                                                :value="inputValue" v-on="inputEvents"
                                                                class="is-rounded_Z" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;" class="emr">
                                        LEMBARAN TINDAK LANJUT
                                    </h1>
                                </div>
                                <div class="column is-12">
                                    <div class="column" style="overflow:auto">
                                        <table class="table-hiet">
                                            <thead>
                                                <tr>
                                                    <th class="th-hie">Tanggal</th>
                                                    <th class="th-hie">
                                                        Keluhan Ibu hamil / menyusui Keadaan Payudara
                                                    </th>
                                                    <th class="th-hie">Penanggulangan</th>
                                                    <th class="th-hie" width="5%">#</th>
                                                </tr>
                                            </thead>
                                            <tbody v-for="(item, index) in input.lembarantindaklanjut" :key="index">
                                                <tr>
                                                    <td class="td-hie">
                                                        <VDatePicker v-model="item.tgltindaklanjut" color="green"
                                                            trim-weeks mode="datetime" :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }"
                                                                class="pb-0">
                                                                <VField>
                                                                    <VControl icon="feather:calendar">
                                                                        <VInput type="text" placeholder="Select a date"
                                                                            :value="inputValue" v-on="inputEvents"
                                                                            class="is-rounded_Z" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </td>
                                                    <td class="td-hie">
                                                        <VField>
                                                            <VControl>
                                                                <VTextarea v-model="item.keluhanmenyusui" rows="2">
                                                                </VTextarea>
                                                            </VControl>
                                                        </VField>
                                                    </td>
                                                    <td class="td-hie">
                                                        <VField>
                                                            <VControl>
                                                                <VTextarea v-model="item.penanggulangan" rows="2">
                                                                </VTextarea>
                                                            </VControl>
                                                        </VField>
                                                    </td>
                                                    <td class="td-hie">
                                                        <div class="column" style="text-align: center;">
                                                            <VIconButton type="button" raised circle icon="feather:plus"
                                                                @click="addNewItem('tindaklanjut')" color="info"
                                                                v-tooltip.bubble="'Tambah '">
                                                            </VIconButton>
                                                            <VIconButton class="mt-1" v-if="index > 0" type="button"
                                                                raised circle icon="feather:trash"
                                                                @click="removeItem(index, 'tindaklanjut')"
                                                                color="danger">
                                                            </VIconButton>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'

useHead({
    title: 'Formulir Kajian Riwayat Menyusui - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

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

const route = useRoute()
const pasien: any = ref({})
const d_pegawai: any = ref([])
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
    airway: [],
    disability: []

})
let allData: any = ref({
    d_kunjungan: [{ value: 1, label: 'Datang Sendiri', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Dikirim dari Poli ANC', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 3, label: 'Dikirim dari RB/RS', isHiddenForm: true, isChecked: false, isHaveForm: true }, { value: 4, label: 'Dikirim dari dr. SpOG', isHiddenForm: true, isChecked: false, isHaveForm: true }, { value: 5, label: 'Dikirim dari dr. SpA', isHiddenForm: true, isChecked: false, isHaveForm: true }, { value: 6, label: 'Dikirim dari Kader ASI', isHiddenForm: true, isChecked: false, isHaveForm: true }],
    d_kehamilan: [{ value: 1, label: 'Diharapkan', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Tidak diharapkan', isHiddenForm: true, isChecked: false, isHaveForm: false }],
    d_pengguguran: [{ value: 1, label: 'Tidak Ada', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Ada, dengan cara', isHiddenForm: true, isChecked: false, isHaveForm: true }],
    d_keadaan: [{ value: 1, label: 'Masih hamil', isHiddenForm: true, isChecked: false, isHaveForm: true, formType: 'text' }, { value: 2, label: 'Sudah melahirkan, tanggal', isHiddenForm: true, isChecked: false, isHaveForm: true, formType: 'date' }],
    d_isanc: [{ value: 1, label: 'Tidak', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Ya, di', isHiddenForm: true, isChecked: false, isHaveForm: true }],
    d_isperawatan: [{ value: 1, label: 'Tidak', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Ya', isHiddenForm: true, isChecked: false, isHaveForm: false }],
    d_kelainanpayudara: [{ value: 1, label: 'Tidak Ada', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Ada, usaha perbaikan', isHiddenForm: true, isChecked: false, isHaveForm: true }],
    d_tempatsalin: [{ value: 1, label: 'RS', isHiddenForm: true, isChecked: false, isHaveForm: true }, { value: 2, label: 'RB', isHiddenForm: true, isChecked: false, isHaveForm: true }, { value: 3, label: 'Rumah', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 4, label: 'Lainnya', isHiddenForm: true, isChecked: false, isHaveForm: true }],
    d_caralahir: [{ value: 1, label: 'Spontan', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Sungsang', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 3, label: 'Ekstraksi Vakum', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 4, label: 'Forceps', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 5, label: 'SC', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 6, label: 'Lainnya', isHiddenForm: true, isChecked: false, isHaveForm: true }],
    d_apgar: [{ value: 1, label: 'Tidak Tahu', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Tahu', isHiddenForm: true, isChecked: false, isHaveForm: true }],
    d_jumlahbayi: [{ value: 1, label: 'Tunggal', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Kembar', isHiddenForm: true, isChecked: false, isHaveForm: false }],
    d_imd: [{ value: 1, label: 'Ya', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Tidak, alasan', isHiddenForm: true, isChecked: false, isHaveForm: true },],
    d_rawatgabung: [{ value: 1, label: 'Ya', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Tidak, alasan', isHiddenForm: true, isChecked: false, isHaveForm: true },],
    d_selainasi: [{ value: 1, label: 'Air Putih', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Lainnya', isHiddenForm: true, isChecked: false, isHaveForm: true },],
    d_kesulitanmenyusui: [{ value: 1, label: 'Tidak Ada', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Ada', isHiddenForm: true, isChecked: false, isHaveForm: true },],
    d_reflekshisapbayi: [{ value: 1, label: 'Baik', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Tidak', isHiddenForm: true, isChecked: false, isHaveForm: false },],
    d_refleksnelanbayi: [{ value: 1, label: 'Baik', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Tidak', isHiddenForm: true, isChecked: false, isHaveForm: false },],
    d_kelainanbayi: [{ value: 1, label: 'Stomatitis', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 2, label: 'Muntah', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 3, label: 'Labio/gnato/palatoskizis', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 4, label: 'Panas', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 5, label: 'Sindrom Down', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 6, label: 'Diare', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 7, label: 'Kelainan Congenital Lain', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 8, label: 'Tongue tie', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 9, label: 'Ikterus', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 10, label: 'BBLR', isHiddenForm: true, isChecked: false, isHaveForm: false }, { value: 11, label: 'Lainnya', isHiddenForm: true, isChecked: false, isHaveForm: true }],
    d_rencanatindak: [{ value: 1, label: 'Bimbingan Langsung', isHiddenForm: true, isChecked: false, isHaveForm: false, }, { value: 2, label: 'Dirujuk langsung, ke', isHiddenForm: true, isChecked: false, isHaveForm: true, formType: 'text', }, { value: 3, label: 'Pengobatan', isHiddenForm: true, isChecked: false, isHaveForm: true, formType: 'text', }, { value: 4, label: 'Kunjungan ulang, tanggal', isHiddenForm: true, isChecked: false, isHaveForm: true, formType: 'date', },],
})

const COLLECTION: any = ref('FormulirKajianRiwayatMenyusui') //table mongodb

const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    riwayatanak: [{
        no: 1,
        urutanak: 1,
    }],
    lembarantindaklanjut: [{
        no: 1,
    }],
    kelainanbayi: [],
    norm: props.pasien.nocm,
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30

})
const isLoading = ref(false)

const d_jeniskelamin: any = ref([{ value: 'L', label: 'Laki - Laki' }, { value: 'P', label: 'Perempuan' }])
const d_ekslusif: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
let dropdownAllo: any = ref([
    "Suami/Istri",
    "Orang tua",
    "Anak",
    "Lainnya"
])

const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
    }
}

const simpan = () => {

    let ID = input.value.id ? input.value.id : ''

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
    console.log(json)

    isLoading.value = true
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
    console.log(norec_emr)
}

const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_pegawai.value = response
    })
}


const addNewItem = (type: string) => {
    if (type == 'riwayatanak') {
        input.value.riwayatanak.push({
            no: input.value.riwayatanak[input.value.riwayatanak.length - 1].no + 1,
        });
    } else if (type == 'tindaklanjut') {
        input.value.lembarantindaklanjut.push({
            no: input.value.lembarantindaklanjut[input.value.lembarantindaklanjut.length - 1].no + 1,
        });
    }
    // ......
}
const removeItem = (index: any, type: string) => {
    if (type == 'riwayatanak') {
        input.value.riwayatanak.splice(index, 1)
    } else if ('tindaklanjut') {
        input.value.lembarantindaklanjut.splice(index, 1)
    }
}

const getDataExist = async () => {
    //
    let params: string = `nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`;
    await useApi().get(`emr/get-riwayat-menyusui?${params}`).then((response) => {
        console.log(response);

        if (response != null || response != undefined) {
            input.value.beratBadan = response.beratBadan ? response.beratBadan : '';
        }
    })
}

const print = async () => {
    H.printBlade(`emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const chunkData = (arr: any, length: number) => {
    let totalChunk = [];
    let chunkLength = parseInt(length, 10);

    if (chunkLength <= 0) {
        H.alert("error", "Terjadi kesalahan");
        return arr;
    }

    for (let i = 0; i < arr.length; i += chunkLength) {
        totalChunk.push(arr.slice(i, i + chunkLength));
    }

    return totalChunk;

}

const handlerCheckbox = (event: any, dObject: string) => {
    let filters = Object.keys(allData.value[dObject]).filter((val) => allData.value[dObject][val].value === event.value);
    let index = filters[0];
    let arrays = allData.value[dObject][index];
    let inputModel: string = dObject.replace("d_", "");

    console.log(input.value[inputModel]);

    if (!Array.isArray(input.value[inputModel])) {
        for (let i = 0; i < allData.value[dObject].length; i++) {
            const element = allData.value[dObject][i];
            if (i !== parseInt(index)) {
                element.isChecked = false;
                element.isHiddenForm = true;
            }
        }
    }

    if (event.isHaveForm || arrays.isHaveForm) {
        if (event.isChecked || arrays.isChecked) {
            arrays.isChecked = false;
            arrays.isHiddenForm = true;
        } else {
            if (arrays.isChecked) {
                arrays.isChecked = false;
                arrays.isHiddenForm = true;
            } else {
                arrays.isChecked = true;
                arrays.isHiddenForm = false;
            }
        }
    } else {
        arrays.isChecked = !arrays.isChecked;
        event.isChecked = !event.isChecked;
    }

    // if (!Array.isArray(input.value[inputModel])) {
    //     input.value[inputModel].push(event);
    // }

    console.log("DATA ARR");
    console.log(arrays);



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


getDataExist()
fetchPasien()


watch(() => [
    input.value.penurunanBB,
    input.value.penurunanNafsuMakan,
], () => {

    let poin1 = input.value.penurunanBB ? parseInt(input.value.penurunanBB.poin) : 0
    let poin2 = input.value.penurunanNafsuMakan ? parseInt(input.value.penurunanNafsuMakan.poin) : 0

    const total = poin1 + poin2
    input.value.totalNilaiMST = total

})

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

// .p-fieldset.p-component{
//     border-left: ;
// }

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
.assesment td {
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

.table.is-borderless {
    border: none !important;
    background-color: transparent;
}

.table.is-borderless th,
tr,
td {
    border: none !important;
    background-color: transparent !important;
}

.label-hie {
    font-weight: 500;
}

.table-hie {
    width: 100%;
    border: 1px solid black
}

.table-hiet {
    width: 130%;
    border: 1px solid black
}

.th-hie,
.td-hie {
    border: 1px solid black;
    padding: 7px;
}

.th-hie {
    text-align: center !important;
}

// tr:hover {
//     background-color: #f5f5f5;
// }</style>
