<template>
    <MasterEMR :isTTD="false" @simpan="simpan()" @simpanTemplate="simpanTemplate()" :ID_PASIEN="ID_PASIEN" :NOREC_PD="NOREC_PD" :norec_emr="norec_emr"
        :input="input" :FORM_NAME="props.FORM_NAME" :FORM_URL="props.FORM_URL" :registrasi="props.registrasi"
        :pasien="props.pasien" :COLLECTION="props.COLLECTION" ref="masterRef" :isLoading="isLoading" :addTemplate="addTemplate">
        <template #content>
            <div class="columns is-multiline m-0">
                <div class="column is-4">
                    <h1 style="font-weight: bold">Ruangan:</h1>
                    <VControl>
                        <VInput type="text" class="input" placeholder="Ruangan" v-model="input.ruangan" disabled />
                    </VControl>
                </div>

                <div class="column is-4">
                    <h1 style="font-weight: bold">Tanggal</h1>
                    <VField>
                        <VDatePicker v-model="input.tanggal" mode="date" style="width: 100%;" trim-weeks>
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

                <div class="column is-4">
                    <h1 style="font-weight: bold">Jam</h1>
                    <VDatePicker v-model="input.Jam" mode="time" is24hr>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:clock" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-12">
                    <VField vertical label="Keluhan Utama">
                        <VControl>
                            <VTextarea
                                v-model="input.keluhanUtama"
                                :rows="3"
                                :cols="30"
                                :placeholder="'Keluhan Utama'"
                                :style="{ width: '100%' }">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 py-0">
                            <span>
                                Anamnesis
                                (Riwayat perjalanan
                                 penyakit sekarang)
                            </span>
                            <VCheckbox
                                v-model="input.CBAnamnesis"
                                color="primary"
                                true-value="auto"
                                label="auto"
                            />
                            <VCheckbox
                                v-model="input.CBAnamnesis"
                                color="primary"
                                true-value="allo"
                                label="allo"
                            />
                        </div>
                        <div class="column is-12">
                            <VField >
                                <VControl>
                                    <VTextarea
                                        v-model="input.TXTAnamnesis"
                                        :rows="3"
                                        :cols="30"
                                        placeholder="Anamnesis"
                                        :style="{ width: '100%' }">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Riwayat Penyakit terdahulu">
                                <VControl>
                                    <VTextarea
                                        v-model="input.TXTPenyakitTerdahulu"
                                        :rows="3"
                                        :cols="30"
                                        placeholder="Penyakit Terdahulu"
                                        :style="{ width: '100%' }">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Riwayat Alergi">
                                <VControl>
                                    <VTextarea
                                        v-model="input.TXTRiawayatAlergi"
                                        :rows="3"
                                        :cols="30"
                                        placeholder="Penyakit Alergi"
                                        :style="{ width: '100%' }">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns is-multiline m-0">
                <div class="column is-6">
                    <h1 style="font-size: 18px; font-weight: bold;">Pemeriksaan Fisik dan Temuan Lain</h1>
                </div>
                <div class="column is-6">
                    <h1 style="font-weight: bold">Tanggal Pemeriksaan</h1>
                    <VField>
                        <VDatePicker v-model="input.tanggalPengkajian" mode="date" style="width: 100%;" trim-weeks>
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
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <h4 style="font-weight: bold;">
                                Airway
                            </h4>
                        </div>
                        <div class="column is-9">
                            <VField horizontal>
                                <VControl raw subcontrol>
                                    <VCheckbox
                                        class="pl-0"
                                        v-model="input.CBAirway"
                                        color="primary"
                                        label="Bebas"
                                        true-value="Bebas"
                                    />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox
                                        class="pl-0"
                                        v-model="input.CBAirway"
                                        color="primary"
                                        label="Non Definitive Airway"
                                        true-value="Non Definitive Airway"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTNonDefinitive" v-if="input.CBAirway == 'Non Definitive Airway'" />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox
                                        class="pl-0"
                                        v-model="input.CBAirway"
                                        color="primary"
                                        label="Bunyi nafas tambahan"
                                        true-value="Bunyi nafas tambahan"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTBunyiNafas" v-if="input.CBAirway == 'Bunyi nafas tambahan'" />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox
                                        class="pl-0"
                                        v-model="input.CBAirway"
                                        color="primary"
                                        label="Definitive Airway"
                                        true-value="Definitive Airway"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTDefinitiveAirway" v-if="input.CBAirway == 'Definitive Airway'" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <h4 style="font-weight: bold;">
                                Breathing
                            </h4>
                        </div>
                        <div class="column is-9">
                            <VField horizontal>
                                <VControl raw subcontrol>
                                    <VCheckbox class="pl-0"
                                        v-model="input.CBBreathing"
                                        color="primary"
                                        label="Spontan"
                                        true-value="Spontan"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTSpontan" v-if="input.CBBreathing == 'Spontan'" />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox class="pl-0"
                                        v-model="input.CBBreathing"
                                        color="primary"
                                        label="Ventilasi Mekanik"
                                        true-value="Ventilasi"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTVentilasi" v-if="input.CBBreathing == 'Ventilasi'" />
                                </VControl>
                            </VField>
                            <div>
                                <h4 class="ml-4">RR :</h4>
                                <VField vertical>
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="pl-0"
                                            v-model="input.CBBreathingRR"
                                            color="primary"
                                            label="Vesikuler"
                                            true-value="Vesikuler"
                                        />
                                        <VField addons v-if="input.CBBreathingRR == 'Vesikuler'">
                                            <VControl expanded>
                                                <VInput
                                                    type="text"
                                                    class="input"
                                                    v-model="input.TXTVesikuler1"
                                                />
                                            </VControl>
                                            <VControl>
                                                <VButton static>
                                                    /
                                                </VButton>
                                            </VControl>
                                            <VControl expanded>
                                                <VInput
                                                    type="text"
                                                    class="input"
                                                    v-model="input.TXTVesikuler2"
                                                />
                                            </VControl>
                                        </VField>
                                    </VControl>
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="pl-0"
                                            v-model="input.CBBreathingRR"
                                            color="primary"
                                            label="Ronchi"
                                            true-value="Ronchi"
                                        />
                                        <VField addons v-if="input.CBBreathingRR == 'Ronchi'">
                                            <VControl expanded>
                                                <VInput
                                                    type="text"
                                                    class="input"
                                                    v-model="input.TXTRonchi1"
                                                />
                                            </VControl>
                                            <VControl>
                                                <VButton static>
                                                    /
                                                </VButton>
                                            </VControl>
                                            <VControl expanded>
                                                <VInput
                                                    type="text"
                                                    class="input"
                                                    v-model="input.TXTRonchi2"
                                                />
                                            </VControl>
                                        </VField>
                                    </VControl>
                                    <VControl raw subcontrol>
                                        <VCheckbox
                                            class="pl-0"
                                            v-model="input.CBBreathingRR"
                                            color="primary"
                                            label="Wheezing"
                                            true-value="Wheezing"
                                        />
                                        <VField addons v-if="input.CBBreathingRR == 'Wheezing'">
                                            <VControl expanded>
                                                <VInput
                                                    type="text"
                                                    class="input"
                                                    v-model="input.TXTWheezing1"
                                                />
                                            </VControl>
                                            <VControl>
                                                <VButton static>
                                                    /
                                                </VButton>
                                            </VControl>
                                            <VControl expanded>
                                                <VInput
                                                    type="text"
                                                    class="input"
                                                    v-model="input.TXTWheezing2"
                                                />
                                            </VControl>
                                        </VField>
                                    </VControl>
                                </VField>
                                <div>
                                    SpO<sub>2</sub> :
                                    <VField addons>
                                        <VControl expanded>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTSp02_1"
                                            />
                                        </VControl>
                                        <VControl>
                                            <VButton static>
                                                %
                                            </VButton>
                                        </VControl>
                                        <VControl expanded>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTSp02_1"
                                            />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3 mt-3">
                            <h4 style="font-weight: bold;">
                                Circulation
                            </h4>
                        </div>
                        <div class="column is-9">
                            <VField horizontal>
                                <div>
                                    TD :
                                    <VField addons class="mt-3 pr-3">
                                        <VControl expanded>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTtd"
                                            />
                                        </VControl>
                                        <VControl>
                                            <VButton static>
                                                mmHg
                                            </VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <VControl raw subcontrol>
                                    <VCheckbox class="mb-0 pb-0"
                                        v-model="input.CBTopangan"
                                        color="primary"
                                        label="Topangan Hemodinamik"
                                        true-value="Topangan Hemodinamik"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTNonDefinitive" v-if="input.CBTopangan == 'Topangan Hemodinamik'" />
                                </VControl>
                            </VField>
                            <VField>
                                <div>
                                    HR :
                                    <VField addons class="mt-3 pr-3">
                                        <VControl expanded>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXThr"
                                            />
                                        </VControl>
                                        <VControl>
                                            <VButton static>
                                                x/mnt
                                            </VButton>
                                        </VControl>
                                    </VField>
                                </div>
                            </VField>
                            <VField>
                                <div>
                                    ECG :
                                    <VField class="mt-3 pr-3">
                                        <VControl>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTecg"
                                            />
                                        </VControl>
                                    </VField>
                                </div>
                            </VField>
                            <VField>
                                <div>
                                    Akses Intravena :
                                    <VField class="mt-3 pr-3">
                                        <VControl>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTintravena"
                                            />
                                        </VControl>
                                    </VField>
                                </div>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3 mt-3">
                            <h4 style="font-weight: bold;">
                                Disability
                            </h4>
                        </div>
                        <div class="column is-9">
                            <VField horizontal>
                                <div>
                                    GCS :
                                    <VField addons class="mt-3 pr-3">
                                        <VControl>
                                            <VButton static disabled>
                                                E
                                            </VButton>
                                        </VControl>
                                        <VControl expanded>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTgcsE"
                                            />
                                        </VControl>
                                        <VControl>
                                            <VButton static disabled>
                                                V
                                            </VButton>
                                        </VControl>
                                        <VControl expanded>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTgcsV"
                                            />
                                        </VControl>
                                        <VControl>
                                            <VButton static disabled>
                                                M
                                            </VButton>
                                        </VControl>
                                        <VControl expanded>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTgcsM"
                                            />
                                        </VControl>
                                    </VField>
                                </div>
                                <VControl raw subcontrol>
                                    <VCheckbox class="mb-0 pb-0"
                                        v-model="input.CBSedasi"
                                        color="primary"
                                        label="Sedasi"
                                        true-value="Sedasi"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTSedasi" v-if="input.CBSedasi == 'Sedasi'" />
                                </VControl>
                            </VField>
                            <VField horizontal>
                                <div>
                                    (AVPU) :
                                    <VField class="mt-3 pr-3">
                                        <VControl>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTAvpu"
                                            />
                                        </VControl>
                                    </VField>
                                </div>
                                <VControl raw subcontrol>
                                    <VCheckbox class="mb-0 pb-0"
                                        v-model="input.CBAnalgetik"
                                        color="primary"
                                        label="Analgetik opioid/sintetik"
                                        true-value="Analgetik opioid/sintetik"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTAnalgetik" v-if="input.CBAnalgetik == 'Analgetik opioid/sintetik'" />
                                </VControl>
                            </VField>
                            <VField>
                                <div>
                                    Pupil : Uk
                                    <VField addons class="mt-3 pr-3">
                                        <VControl expanded>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTuk1"
                                            />
                                        </VControl>
                                        <VControl>
                                            <VButton static disabled>
                                                mm/
                                            </VButton>
                                        </VControl>
                                        <VControl expanded>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTuk2"
                                            />
                                        </VControl>
                                        <VControl>
                                            <VButton static disabled>
                                                mm
                                            </VButton>
                                        </VControl>
                                    </VField>
                                </div>
                            </VField>
                            <VField>
                                <div>
                                    RC
                                    <VField addons class="mt-3 pr-3">
                                        <VControl expanded>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTrc1"
                                            />
                                        </VControl>
                                        <VControl>
                                            <VButton static disabled>
                                                /
                                            </VButton>
                                        </VControl>
                                        <VControl expanded>
                                            <VInput
                                                type="text"
                                                class="input"
                                                v-model="input.TXTrc2"
                                            />
                                        </VControl>
                                    </VField>
                                </div>
                            </VField>
                            <VField label="Skala Nyeri">
                                <VControl>
                                    <VInput
                                        type="text"
                                        class="input"
                                        v-model="input.TXTSkalaNyeri"
                                    />
                                </VControl>
                            </VField>
                            <VField label="Edema">
                                <VControl>
                                    <VInput
                                        type="text"
                                        class="input"
                                        v-model="input.TXTEdema"
                                    />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3 mt-3">
                            <h4 style="font-weight: bold;">
                                Electrolyte
                            </h4>
                        </div>
                        <div class="column is-9">
                            <VField horizontal>
                                <div>
                                    Turgor
                                    <VField class="mt-3 pr-3">
                                        <VControl>
                                            <VInput type="text" class="input" placeholder=""
                                            v-model="input.TXTTurgor"/>
                                        </VControl>
                                    </VField>
                                </div>
                                <VControl raw subcontrol>
                                    <VCheckbox class="mb-0 pb-0"
                                        v-model="input.CBelektrolit"
                                        color="primary"
                                        label="Koreksi elektrolit"
                                        true-value="Koreksi elektrolit"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTElektrolit" v-if="input.CBelektrolit == 'Koreksi elektrolit'" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3 mt-3">
                            <h4 style="font-weight: bold;">
                                Fluid
                            </h4>
                        </div>
                        <div class="column is-9">
                            <VField horizontal>
                                <VControl raw subcontrol>
                                    <VCheckbox class="mb-0 pb-0 pl-0"
                                        v-model="input.CBFluid"
                                        color="primary"
                                        label="Kateter urin (CM/CK/BC/BK (mL)"
                                        true-value="Kateter urin"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTKateterUrin" v-if="input.CBFluid == 'Kateter urin'" />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox class="mb-0 pb-0"
                                        v-model="input.CBFluid"
                                        color="primary"
                                        label="Terapi Cairan"
                                        true-value="Terapi Cairan"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTterapicairan" v-if="input.CBFluid == 'Terapi Cairan'" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3 mt-3">
                            <h4 style="font-weight: bold;">
                                Gastrointestinal
                            </h4>
                        </div>
                        <div class="column is-9">
                            <VField horizontal>
                                <div>
                                    Bising usus (normal/abnormal)* BAB
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox class="mb-0 pb-0 pl-0"
                                                v-model="input.CBbisingUsus"
                                                color="primary"
                                                label="NGT"
                                                true-value="NGT"
                                            />
                                        </VControl>
                                    </VField>
                                </div>

                                <VControl raw subcontrol>
                                    <VCheckbox class="mb-0 pb-0"
                                        v-model="input.CBbisingUsus"
                                        color="primary"
                                        label="Nutrisi"
                                        true-value="Nutrisi"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTBisingUsus" v-if="input.CBbisingUsus == 'Nutrisi'" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3 mt-3">
                            <h4 style="font-weight: bold;">
                                Hematology
                            </h4>
                        </div>
                        <div class="column is-9">
                            <VField horizontal>
                                <VControl raw subcontrol>
                                    <VCheckbox class="mb-0 pb-0 pl-0"
                                        v-model="input.CBHematologi"
                                        color="primary"
                                        label="Suhu"
                                        true-value="Suhu"
                                    />
                                    <VField addons v-if="input.CBHematologi == 'Suhu'" >
                                        <VControl expanded>
                                            <VInput type="text" class="input" placeholder=""
                                            v-model="input.TXTSuhu"/>
                                        </VControl>
                                        <VControl>
                                            <VButton static disabled>
                                                <sup>o</sup>C
                                            </VButton>
                                        </VControl>
                                    </VField>
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox class="mb-0 pb-0 pl-0"
                                        v-model="input.CBHematologi"
                                        color="primary"
                                        label="Lokasi infeksi"
                                        true-value="Lokasi infeksi"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTLokasiInfeksi" v-if="input.CBHematologi == 'Lokasi infeksi'" />
                                </VControl>
                                <VControl raw subcontrol>
                                    <VCheckbox class="mb-0 pb-0 pl-0"
                                        v-model="input.CBHematologi"
                                        color="primary"
                                        label="Antibiotik"
                                        true-value="Antibiotik"
                                    />
                                    <VInput type="text" class="input" placeholder=""
                                    v-model="input.TXTAntibiotik" v-if="input.CBHematologi == 'Antibiotik'" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns is-multiline mx-0 mt-5">
                <table border="1" width="100%">
                    <thead>
                        <tr>
                            <th class="has-text-centered">
                                No
                            </th>
                            <th class="has-text-centered">
                                <h4 style="font-weight: bold;">
                                    Diagnosis
                                </h4>
                            </th>
                            <th class="has-text-centered">
                                <h4 style="font-weight: bold;">
                                    Prioritas Masuk
                                </h4>
                            </th>
                            <th class="has-text-centered">
                                #
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in input.details.diagnosa" :key="index">
                            <td class="has-text-centered pt-3">
                                {{ index + 1 }}
                            </td>
                            <td class="has-text-centered p-3">
                                <VField>
                                    <VControl>
                                        <VInput
                                            type="text"
                                            class="input"
                                            placeholder="Nama Diagnosis"
                                            v-model="item.diagnosis"
                                        />
                                    </VControl>
                                </VField>
                            </td>
                            <td class="has-text-centered p-3">
                                <VField>
                                    <VControl>
                                        <VSelect v-model="item.prioritas">
                                            <VOption value="" disabled>
                                                Pilih Prioritas
                                            </VOption>
                                            <VOption
                                                v-for="(prioritas, index) in d_prioritas"
                                                :key="index"
                                                :value="prioritas.value"
                                                :label="prioritas.label">
                                            </VOption>
                                        </VSelect>
                                    </VControl>
                                </VField>
                            </td>
                            <td class="has-text-centered pt-3">
                                <VIconButton type="button" raised circle icon="feather:plus" @click="addNewDagnosa()"
                                color="info" v-tooltip.bubble="'Tambah '">
                                </VIconButton>
                                <VIconButton v-if="index > 0" type="button"
                                    raised circle icon="feather:trash"
                                    @click="input.details.diagnosa.splice(index, 1)" color="danger">
                                </VIconButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="columns is-multiline mx-0 mt-5">
                <table border="1" width="100%">
                    <thead>
                        <tr>
                            <th class="has-text-centered">
                                No
                            </th>
                            <th class="has-text-centered">
                                <h4 style="font-weight: bold;">
                                    Daftar Masalah
                                </h4>
                            </th>
                            <th class="has-text-centered">
                                <h4 style="font-weight: bold;">
                                    Rencana Intervensi
                                </h4>
                            </th>
                            <th class="has-text-centered">
                                <h4 style="font-weight: bold;">
                                    Target
                                </h4>
                            </th>
                            <th class="has-text-centered">
                                #
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in input.details.daftarMasalah" :key="index">
                            <td class="has-text-centered pt-3">
                                {{ index + 1 }}
                            </td>
                            <td class="has-text-centered p-3">
                                <VField>
                                    <VControl>
                                        <VInput
                                            type="text"
                                            class="input"
                                            placeholder="Daftar Masalah"
                                            v-model="item.masalah"
                                        />
                                    </VControl>
                                </VField>
                            </td>
                            <td class="has-text-centered p-3">
                                <VField>
                                    <VControl>
                                        <VInput
                                            type="text"
                                            class="input"
                                            placeholder="Rencana Intervensi"
                                            v-model="item.rencanaIntervensi"
                                        />
                                    </VControl>
                                </VField>
                            </td>
                            <td class="has-text-centered p-3">
                                <VField>
                                    <VControl>
                                        <VInput
                                            type="text"
                                            class="input"
                                            placeholder="Target"
                                            v-model="item.target"
                                        />
                                    </VControl>
                                </VField>
                            </td>
                            <td class="has-text-centered pt-3">
                                <VIconButton type="button" raised circle icon="feather:plus" @click="addNewDaftarMasalah()"
                                color="info" v-tooltip.bubble="'Tambah '">
                                </VIconButton>
                                <VIconButton v-if="index > 0" type="button"
                                    raised circle icon="feather:trash"
                                    @click="input.details.daftarMasalah.splice(index, 1)" color="danger">
                                </VIconButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="columns is-multiline mx-0 mt-5">
                <h4 style="font-weight: bold; font-size: 15px">
                    Intruksi :
                </h4>
                <div class="column is-12">
                    <VField>
                        <VControl>
                            <VTextarea
                                type="text"
                                class="input"
                                placeholder="Intruksi"
                                v-model="input.intruksi"
                            />
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="columns is-multiline mx-0 mt-5">
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                        </div>
                        <div class="column is-6">
                            <div style="text-align:center;">
                                <h1>Dokter Penanggung Jawab Pelayanan</h1>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.dpjp" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" class="mt-2" />
                                </VControl>
                            </div>
                        </div>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const pasien: any = ref({})
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const masterRef = ref(null)
const dataPasien = '';
const d_Dokter: any = ref([]);
const NOREC_EMRPASIEN: any = ref('')
const isLoading: any = ref(false);
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const d_prioritas: any = ref([
    { label: '1', value: 1 },
    { label: '2', value: 2 },
    { label: '3', value: 3 },
])


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
    details: {
        diagnosa: [
            {diagnosis: null, prioritas: null}
        ],
        daftarMasalah: [
            {masalah: null, rencanaIntervensi: null, target: null}
        ]
    },
    // tglnegatif: new Date(),
    // tglpositif: new Date(),
    // detail: [
    //     {
    //         no: 1,
    //         value: ''
    //     }
    // ],
})

const setAutoFill = async () => {
    input.value.DPJP = props.registrasi.dokter
    input.value.DokterPenanggungJawab = props.registrasi.dokter
    input.value.ruangan = props.registrasi.namaruangan
};
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

const loadRiwayat = async () => {
    isLoading.value = true;
        await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        isLoading.value = false;
        if (response.length) {
            input.value = response[0] //set ke inputan
            if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
            }
            dataTTD.value = response[0]
            H.tandaTangan().set('TTDpasien', dataTTD.value.TTDpasien)
        }
        else {
            setAutoFill()
        }
    })
}

const fetchDokter = async (filter: any) => {
    d_Dokter.value = await H.fetchDokter(filter);
}

const triggerAllData = async () => {
    if (masterRef.value) {
        let ss = await masterRef.value.loadRiwayat()
        if (ss != null) {
            input.value = ss
        }
    }
}

const addNewDagnosa = () => {
    input.value.details.diagnosa.push({diagnosis: null, prioritas: null})
}

const addNewDaftarMasalah = () => {
    input.value.details.daftarMasalah.push({masalah: null, rencanaIntervensi: null, target: null})
}

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    return;
  }
  let ID = input.id ? input.id : ''
  let object: any = {}

  object = input.value
  object.nocm = pasien.value.nocm

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

  useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    input.value.namatemplate = null
  }).catch((e: any) => {
    isLoading.value = false
  })
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
const addTemplate = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  input.value['id'] = ''
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

onMounted(() => {
    setAutoFill();
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
