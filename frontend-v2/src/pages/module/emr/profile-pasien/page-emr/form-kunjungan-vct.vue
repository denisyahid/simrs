<template>
    <MasterEMR :isTTD="false" @simpan="simpan()" :ID_PASIEN="ID_PASIEN" :NOREC_PD="NOREC_PD" :norec_emr="norec_emr"
        :input="input" :FORM_NAME="props.FORM_NAME" :FORM_URL="props.FORM_URL" :registrasi="props.registrasi"
        :pasien="props.pasien" :COLLECTION="props.COLLECTION" ref="masterRef" :isLoading="isLoading">
        <template #content>
            <div class="column is-12 mt-3">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField label="Nama Pasien">
                            <VControl>
                                <VInput type="text" v-model="input.namapasien" :value="props.pasien.namapasien"
                                    disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Nomor Registrasi">
                            <VControl>
                                <VInput type="text" v-model="input.noreg" :value="props.registrasi.noregistrasi"
                                    disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="No. RM">
                            <VControl>
                                <VInput type="text" v-model="input.nocm" :value="props.pasien.nocm" disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
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
                                <VInput type="text" v-model="input.umur" :value="props.pasien.umur" disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField label="Tanggal Datang">
                            <VControl>
                                <VInput type="text" v-model="input.tglregistrasi"
                                    :value="props.registrasi.tglregistrasi" disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Tanggal Kunjungan">
                            <VControl>
                                <VInput type="text" v-model="input.tglkunjungan" :value="props.registrasi.tglregistrasi"
                                    disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Alamat">
                            <VControl>
                                <VInput type="text" v-model="input.alamat" :value="props.pasien.alamatlengkap"
                                    disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField label="Kota/ Kabupaten">
                            <VControl>
                                <VInput type="text" v-model="input.kota" :value="props.pasien.tempatlahir"
                                    disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Telp">
                            <VControl>
                                <VInput type="text" v-model="input.nohp" :value="props.registrasi.nohp"
                                    disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Suku">
                            <VControl>
                                <VInput type="text" v-model="input.suku" :value="props.pasien.suku" disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField label="Agama">
                            <VControl>
                                <VInput type="text" v-model="input.agama" :value="props.pasien.agama" disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Pendidikan">
                            <VControl>
                                <VInput type="text" v-model="input.pendidikan" :value="props.pasien.pendidikan"
                                    disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Pekerjaan">
                            <VControl>
                                <VInput type="text" v-model="input.pekerjaan" :value="props.pasien.pekerjaan"
                                    disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField label="Status Perkawinan">
                            <VControl>
                                <VInput type="text" v-model="input.namaayah" :value="props.pasien.namaayah"
                                    disabled="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="No Reg.Nas">
                            <VControl>
                                <VInput type="text" v-model="input.noregnas" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Tanggal Positif">
                            <VTextarea rows="1" v-model="input.tglpositif"></VTextarea>
                            <!-- <VDatePicker v-model="input.tglpositif" mode="datetime" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker> -->
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField label="Tanggal Negatif">
                            <VTextarea rows="1" v-model="input.tglnegatif"></VTextarea>
                            <!-- <VDatePicker v-model="input.tglnegatif" mode="dateTime" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker> -->
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Sub tipe virus">
                            <VControl>
                                <VInput type="text" v-model="input.virus" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Tinggi Badan">
                            <VControl>
                                <VInput type="text" v-model="input.tinggibadan" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VField label="IMT">
                            <VControl>
                                <VInput type="text" v-model="input.imt" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12 mt-3 px-5">
                <div class="columns is-multiline" v-for="headerForm in formGenerator">
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">{{ headerForm.header }}</h1>
                    </div>
                    <div class="column" v-for="formBody in headerForm.body" :class="formBody.class">
                        <VField :class="formBody.type == 'checkbox' ? 'is-flex' : ''"
                            :label="formBody.type != 'checkbox' ? formBody.label : ''" style="width: auto !important;">
                            <VControl fullwidth v-if="formBody.type == 'dropdown'">
                                <VSelect v-model="input[formBody.model]">
                                    <VOption v-for="formValue in formBody.children" :value="formValue.value">
                                        {{ formValue.label }}
                                    </VOption>
                                </VSelect>
                            </VControl>
                            <VControl fullwidth v-if="formBody.type == 'text'">
                                <VInput v-model="input[formBody.model]" :disabled="formBody.disabled" type="text" />
                            </VControl>
                            <div class="columns is-multiline" v-if="formBody.type == 'checkbox'">
                                <span v-if="formBody.label != ''">
                                    {{ formBody.label }}
                                </span>
                                <div class="column is-3 pt-0" v-for="formValue in formBody.children">
                                    <VControl fullwidth>
                                        <VRadio class="fontcheckbox" v-model="input[formBody.model]"
                                            :value="formValue.label" :label="formValue.label" :name="formBody.model"
                                            color="primary" square />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline" v-if="formBody.type == 'checkboxMulti'">
                                <span v-if="formBody.label != ''">
                                  {{ formBody.label }}
                                </span>
                                <div class="column is-3 pt-0" v-for="(formValue, index) in formBody.children" :key="index">
                                  <VControl fullwidth>
                                    <VControl raw subcontrol>
                                      <VCheckbox
                                            class="p-0"
                                            color="primary"
                                            square
                                            :true-value="formValue.label"
                                            :label="formValue.label"
                                            v-model="input[`${formBody.model}${index}`]"
                                        />
                                    </VControl>
                                  </VControl>
                                </div>
                              </div>
                            <div class="columns is-multiline" v-if="formBody.type == 'checkboxSatuan'">
                                <div class="column is-3 pt-0" v-for="formValue in formBody.children">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :value="formValue.label"
                                            :label="formValue.label" v-model="input[formValue.value]" />
                                    </VControl>
                                </div>
                            </div>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-12 pb-0">
                        <h1 class="bold" style="font-size:larger;">
                            PEMERIKSAAN FISIK :
                        </h1>
                    </div>
                    <div class="column is-12 mt-5 mb-0 pb-0">
                        <h1 class="">GCS : </h1>
                    </div>
                    <div class="column is-3">
                        <VField addons>
                            <VControl class="field-addon-body">
                                <VButton static>E</VButton>
                            </VControl>
                            <VControl expanded>
                                <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E" label="label"
                                    :options="d_gcse" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off" style="border-radius:0px 4px 4px 0px;height:100%">
                                </Multiselect>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField addons>
                            <VControl class="field-addon-body">
                                <VButton static>V</VButton>
                            </VControl>
                            <VControl expanded>
                                <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V" label="label"
                                    :options="d_gcsv" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off" style="border-radius:0px 4px 4px 0px;height:100%">
                                </Multiselect>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField addons>
                            <VControl class="field-addon-body">
                                <VButton static>M</VButton>
                            </VControl>
                            <VControl expanded>
                                <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M" label="label"
                                    :options="d_gcsm" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off" style="border-radius:0px 4px 4px 0px;height:100%">
                                </Multiselect>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3" style="margin-top: -20px;">
                        <h1>Tekanan Darah</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Tekanan Darah"
                                    v-model="input.tekananDarahObgyn" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>mmHg</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <h1>Nadi</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="number" class="input" placeholder="" v-model="input.nadiObgyn" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/menit</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <h1>Respirasi</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="number" class="input" placeholder="" v-model="input.nafasObgyn" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/menit</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <h1>Suhu</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="number" class="input" placeholder="" v-model="input.celciusObgyn" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>°C </VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <h1>Berat Badan</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="number" class="input" placeholder="Berat Badan"
                                    v-model="input.beratbadanObgyn" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>kg</VButton>
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1>Mata</h1>
                <div class="columns column is-12 is-multiline pb-0 pt-1 pl-0">
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.mata" true-value="Icterus"
                                    label="Icterus" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.mata" true-value="Cowong"
                                    label="Cowong" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.mata" true-value="Anemi"
                                    label="Anemi" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1>THT</h1>
                <div class="columns column is-12 is-multiline pb-0 pt-1 pl-0">
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tht" true-value="Candidiasis"
                                    label="Candidiasis" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tht" true-value="Ulkus"
                                    label="Ulkus" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.tht" true-value="Fissure"
                                    label="Fissure" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1>Gigi</h1>
                <div class="columns column is-12 is-multiline pb-0 pt-1 pl-0">
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.gigi" true-value="Ganggren"
                                    label="Ganggren" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.gigi" true-value="Caries"
                                    label="Caries" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.gigi" true-value="Lepas"
                                    label="Lepas" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.gigi" true-value="Rapuh"
                                    label="Rapuh" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1>Paru</h1>
                <div class="columns column is-12 is-multiline pb-0 pt-1 pl-0">
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.paru" true-value="Rh. Kering"
                                    label="Rh. Kering" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.paru" true-value="Rh. Basah"
                                    label="Rh. Basah" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.paru" true-value="Wheezing"
                                    label="Wheezing" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.paru" true-value="Normal"
                                    label="Normal" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.paru" true-value="Redup"
                                    label="Redup" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.paru" true-value="Hipersonor"
                                    label="Hipersonor" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.paru" true-value="Nyeri"
                                    label="Nyeri" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1>Jantung</h1>
                <div class="columns column is-12 is-multiline pb-0 pt-1 pl-0">
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.jantung" true-value="Murmur"
                                    label="Murmur" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.jantung"
                                    true-value="Cardiomegali" label="Cardiomegali" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.jantung"
                                    true-value="Friction Rub" label="Friction Rub" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.jantung"
                                    true-value="Miokarditis" label="Miokarditis" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1>Abdomen</h1>
                <div class="columns column is-12 is-multiline pb-0 pt-1 pl-0">
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.abdomen"
                                    true-value="Potomegali" label="Potomegali" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.abdomen" true-value="Sirosis"
                                    label="Sirosis" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.abdomen"
                                    true-value="Splenomegali" label="Splenomegali" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.abdomen" true-value="Asoites"
                                    label="Asoites" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.abdomen"
                                    true-value="Kolesterol" label="Kolesterol" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <h1>Ekstremitas</h1>
                <div class="columns column is-12 is-multiline pb-0 pt-1 pl-0">
                    <div class="column is-3">
                        <VField vertical>
                            <VControl>
                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.jantung" true-value="Edema"
                                    label="Edema" color="primary" circle />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12 mt-3 px-5">
                <div class="columns is-multiline" v-for="headerForm in formGenerator2">
                    <div class="column is-12">
                        <h1 style="font-weight: bold;">{{ headerForm.header }}</h1>
                    </div>
                    <div class="column" v-for="formBody in headerForm.body" :class="formBody.class">
                        <VField :class="formBody.type == 'checkbox' ? 'is-flex' : ''"
                            :label="formBody.type != 'checkbox' ? formBody.label : ''" style="width: auto !important;">
                            <VControl fullwidth v-if="formBody.type == 'dropdown'">
                                <VSelect v-model="input[formBody.model]">
                                    <VOption v-for="formValue in formBody.children" :value="formValue.value">
                                        {{ formValue.label }}
                                    </VOption>
                                </VSelect>
                            </VControl>
                            <VControl fullwidth v-if="formBody.type == 'text'">
                                <VInput v-model="input[formBody.model]" :disabled="formBody.disabled" type="text" />
                            </VControl>
                            <div class="columns is-multiline" v-if="formBody.type == 'checkbox'">
                                <span v-if="formBody.label != ''">
                                    {{ formBody.label }}
                                </span>
                                <div class="column is-3 pt-0" v-for="formValue in formBody.children">
                                    <VControl fullwidth>
                                        <VRadio class="fontcheckbox" v-model="input[formBody.model]"
                                            :value="formValue.label" :label="formValue.label" :name="formBody.model"
                                            color="primary" square />
                                    </VControl>
                                </div>
                            </div>
                            <div class="columns is-multiline" v-if="formBody.type == 'checkboxSatuan'">
                                <div class="column is-3 pt-0" v-for="formValue in formBody.children">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square :value="formValue.label"
                                            :label="formValue.label" v-model="input[formValue.value]" />
                                    </VControl>
                                </div>
                            </div>
                        </VField>
                    </div>
                </div>
            </div>
        </template>
    </MasterEMR>
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

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const masterRef = ref(null)
const dataPasien = '';
const d_Dokter: any = ref([]);
const dataTTD: any = ref([])
const NOREC_EMRPASIEN: any = ref('')
const isLoading: any = ref(false);
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const formGenerator: any = ref([]);
const formGenerator2: any = ref([]);
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const riskFactors: any = ref([
    { label: 'IVDU', value: '' },
    { label: 'Heteroseksual', value: '' },
    { label: 'Homoseksual', value: '' },
    { label: 'Transgender', value: '' },
    { label: 'Biseksual', value: '' },
    { label: 'Tato', value: '' },
    { label: 'Perinatal', value: '' },
    { label: 'Transfusi/ Cangkok', value: '' },
    { label: 'Pasangan Heterosex', value: '' },
    { label: 'Pasangan IVDU', value: '' },
    { label: 'Pasangan multiple risk', value: '0' },
]);


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
    // tglnegatif: new Date(),
    // tglpositif: new Date(),
    // detail: [
    //     {
    //         no: 1,
    //         value: ''
    //     }
    // ],
})

console.log("PROPS LUAR MASTER", props)
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    console.log("Pasien", props.pasien);
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }

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


const triggerAllData = async () => {
    if (masterRef.value) {
        let ss = await masterRef.value.loadRiwayat()
        if (ss != null) {
            input.value = ss
            if(input.value.faktorResiko) {
                const matchingIndex = riskFactors.value.findIndex(child => child.label === input.value.faktorResiko);

                if (matchingIndex !== -1) {
                    input.value[`faktorResiko${matchingIndex}`] = riskFactors.value[matchingIndex].label;
                }
            }
        } else {
            const NS = await useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + "&field=tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,keluhanutama,riwayatpenyakit,riwayatpenyakitdahulu,riwayatpengobatan,riwayatpenyakitkeluarga,riwayatalergi,beratbadanObgyn,tinggibadanObgyn")
            if (NS != null) {
                input.value.tekananDarahObgyn = NS.tekananDarahObgyn
                input.value.nadiObgyn = NS.nadiObgyn
                input.value.nafasObgyn = NS.nafasObgyn
                input.value.celciusObgyn = NS.celciusObgyn
                // input.value.sao2Obgyn = NS.sao2Obgyn
                // input.value.tinggibadanObgyn = NS.tinggibadanObgyn
                input.value.beratbadanObgyn = NS.beratbadanObgyn
                input.value.gcse = NS.gcse
                input.value.gcsv = NS.gcsv
                input.value.gcsm = NS.gcsm
            }
        }
    }
}

const prepareData = () => {
    formGenerator.value = [
        {
            header: 'Faktor Resiko',
            class: [],
            body: [
                {
                    label: '',
                    type: 'checkboxMulti',
                    model: 'faktorResiko',
                    class: ['is-12'],
                    children: [
                        {
                            label: 'IVDU',
                            value: '',
                        },
                        {
                            label: 'Heteroseksual',
                            value: '',
                        },
                        {
                            label: 'Homoseksual',
                            value: '',
                        },
                        {
                            label: 'Transgender',
                            value: '',
                        },
                        {
                            label: 'Biseksual',
                            value: '',
                        },
                        {
                            label: 'Tato',
                            value: '',
                        },
                        {
                            label: 'Perinatal',
                            value: '',
                        },
                        {
                            label: 'Transfusi/ Cangkok',
                            value: '',
                        },
                        {
                            label: 'Pasangan Heterosex',
                            value: '',
                        },
                        {
                            label: 'Pasangan IVDU',
                            value: '',
                        },
                        {
                            label: 'Pasangan multiple risk',
                            value: '0',
                        },
                    ],
                },
            ]
        },
        {
            header: 'Status Hepatitis',
            class: [],
            body: [
                {
                    
                    label: '',
                    type: 'checkboxSatuan',
                    model: 'keluhan',
                    class: ['is-12'],
                    children: [
                        {
                            label: 'Hepatitis B',
                            value: 'hepatitisB',
                        },
                        {
                            label: 'Hepatitis C',
                            value: 'hepatitisC',
                        },
                    ],
                },
                {
                    label: '',
                    type: 'text',
                    model: 'hepatitis_lainnya',
                },
                {
                    label: '',
                    type: 'dropdown',
                    model: 'statushepatitis',
                    class: ['is-12'],
                    children: [
                        {
                            label: 'Reaktif',
                            value: 'Reaktif',
                        },
                        {
                            label: 'Non Reaktif',
                            value: 'Non Reaktif',
                        },
                        {
                            label: 'Belum Pernah Periksa',
                            value: 'Belum Pernah Periksa',
                        }
                    ],
                },
            ]
        },
        {
            header: 'Keluhan',
            class: [],
            body: [
                {
                    label: '',
                    type: 'checkboxSatuan',
                    model: 'keluhan',
                    class: ['is-12'],
                    children: [
                        {
                            label: 'BB Turun',
                            value: 'BB Turun',
                        },
                        {
                            label: 'Diare',
                            value: 'Diare',
                        },
                        {
                            label: 'Badan Panas',
                            value: 'Badan Panas',
                        },
                        {
                            label: 'Jamur dimulut',
                            value: 'Jamur dimulut',
                        },
                        {
                            label: 'Sulit menelan',
                            value: 'Sulit menelan',
                        },
                        {
                            label: 'Batuk',
                            value: 'Batuk',
                        },
                        {
                            label: 'Gatal pada kulit',
                            value: 'Gatal pada kulit',
                        },
                        {
                            label: 'Kelainan kulit',
                            value: 'Kelainan kulit',
                        },
                        {
                            label: 'Gangguan visus',
                            value: 'Gangguan visus',
                        },
                        {
                            label: 'Herpes simplex',
                            value: 'Herpes simplex',
                        },
                        {
                            label: 'Herpes zoster',
                            value: 'Herpes zoster',
                        },
                        {
                            label: 'ISPA berulang',
                            value: 'ISPA berulang',
                        },
                        {
                            label: 'Nyeri kepala',
                            value: 'Nyeri kepala',
                        },
                        {
                            label: 'TB paru',
                            value: 'TB paru',
                        },
                        {
                            label: 'IMS',
                            value: 'IMS',
                        },
                        {
                            label: 'Infeksi paru non TB',
                            value: 'Infeksi paru non TB',
                        },
                        {
                            label: 'Kesadaran menurun',
                            value: 'Kesadaran menurun',
                        },
                        {
                            label: 'Pembesaran KGB',
                            value: 'Pembesaran KGB',
                        },
                    ],
                },
            ]
        },
        {
            header: '',
            class: [],
            body: [
                {
                    label: 'Keluhan Lain',
                    type: 'text',
                    model: 'keluhanlain',
                    class: ['is-6'],
                    children: [],
                },
                {
                    label: 'Lokasi KGB',
                    type: 'text',
                    model: 'lokasiKGB',
                    class: ['is-6'],
                    children: [],
                },
            ]
        },
    ]
    formGenerator2.value = [
        {
            header: 'AIDS Defening illnes',
            class: [],
            body: [
                {
                    label: '',
                    type: 'checkboxSatuan',
                    model: 'aidsdefening',
                    class: ['is-12'],
                    children: [
                        {
                            label: 'Lymphoma Burkitt`s',
                            value: 'LymphomaBurkitt',
                        },
                        {
                            label: 'Cocodiodomycosis',
                            value: 'Cocodiodomycosis',
                        },
                        {
                            label: 'Cytomegalovirus',
                            value: 'Cytomegalovirus',
                        },
                        {
                            label: 'Herpes simplex',
                            value: 'Herpessimplex',
                        },
                        {
                            label: 'Kandidiasis Esofagus',
                            value: 'KandidiasisEsofagus',
                        },
                        {
                            label: 'Cryptococcosis',
                            value: 'Cryptococcosis',
                        },
                        {
                            label: 'Sarkoma Kaposi',
                            value: 'SarkomaKaposi',
                        },
                        {
                            label: 'Histoplasmosis',
                            value: 'Histoplasmosis',
                        },
                        {
                            label: 'Ca Cervix invasif',
                            value: 'CaCervixinvasif',
                        },
                        {
                            label: 'Cryptosporidiosis',
                            value: 'Cryptosporidiosis',
                        },
                        {
                            label: 'HIV encephalopathy',
                            value: 'HIVencephalopathy',
                        },
                        {
                            label: 'Isosporiasis',
                            value: 'Isosporiasis',
                        },
                        {
                            label: 'HIV Wasting syndrome',
                            value: 'HIV Wasting syndrome',
                        },
                        {
                            label: 'Penicilliosis',
                            value: 'Penicilliosis',
                        },
                        {
                            label: 'Reccurent pneumonia',
                            value: 'Reccurentpneumonia',
                        },
                        {
                            label: 'Lymphoma Otak',
                            value: 'LymphomaOtak',
                        },
                        {
                            label: 'Salmonella septicemia',
                            value: 'Salmonellasepticemia',
                        },
                        {
                            label: 'PCP',
                            value: 'PCP',
                        },
                        {
                            label: 'M. Tuberculosis complex',
                            value: 'MTuberculosiscomplex',
                        },
                        {
                            label: 'Toxoplasmosis',
                            value: 'Toxoplasmosis',
                        },
                        {
                            label: 'Lymphoma Immunoblaastic',
                            value: 'LymphomaImmunoblaastic',
                        },
                        {
                            label: 'Cytomegalovirus retintis',
                            value: 'Cytomegalovirusretintis',
                        },
                        {
                            label: 'Kandidiasis trakea/broncus/paru',
                            value: 'Kandidiasistrakea/broncus/paru',
                        },
                        {
                            label: 'Progressive Multifocal Leukeuncephalopathy',
                            value: 'ProgressiveMultifocalLeukeuncephalopathy',
                        },
                        {
                            label: 'Mycobacterium non TBC',
                            value: 'MycobacteriumnonTBC',
                        },
                    ],
                },
            ]
        },
    ]

    input.value.namapasien = props.pasien.namapasien
    input.value.noreg = props.registrasi.noregistrasi
    input.value.nocm = props.pasien.nocm
    input.value.tgllahir = props.pasien.tgllahir
    input.value.jeniskelamin = props.pasien.jeniskelamin
    input.value.umur = props.pasien.umur
    input.value.tglregistrasi = props.registrasi.tglregistrasi
    input.value.tglkunjungan = props.registrasi.tglregistrasi
    input.value.alamat = props.pasien.alamatlengkap
    input.value.kota = props.pasien.kota
    input.value.nohp = props.pasien.nohp
    input.value.suku = props.pasien.suku
    input.value.agama = props.pasien.agama
    input.value.pendidikan = props.pasien.pendidikan
    input.value.noreg = props.pasien.pekerjaan
    input.value.namaayah = props.pasien.namaayah ? 'Sudah Kawin' : 'Belum Kawin'
}

onMounted(() => {

    prepareData();
    triggerAllData()
    setView()
})


</script>

<style lang="scss">
.bold {
    font-weight: bold;
}

mark {
    background-color: yellow;
    /* Pastikan warna yang Anda inginkan ditulis di sini */
    color: black;
    /* Warna teks jika perlu */
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
    /* Firefox */
}
</style>
