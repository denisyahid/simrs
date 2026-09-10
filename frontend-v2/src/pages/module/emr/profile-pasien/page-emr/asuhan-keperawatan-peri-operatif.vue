<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @kembaliKeun="kembaliKeun" @simpanTemplate="simpanTemplate"></ButtonEmr>
                    </div>
                </div>
            </div>

            <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
                @close="isAlltemplate = false; showModalTemplateFix = false">
                <template #content>
                    <DataTable :pt="{
                        table: { style: 'min-width: 50rem; min-height: 10rem;' },
                        column: {
                            bodycell: ({ state }) => ({
                                class: [{ 'pt-0 pb-0': state['d_editing'] }]
                            })
                        }
                    }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="10"
                        paginator tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
                        :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack"
                        breakpoint="960px">
                        <template #header>
                            <div class="columns is-multiline">
                                <div class="column is-8">
                                    <VField>
                                        <InputText v-model="filtersTemplate['global'].value"
                                            placeholder="Search Nama Template" />
                                    </VField>
                                </div>
                                <div class="column is-4"></div>
                            </div>
                        </template>
                        <template #empty> No template found. </template>
                        <template #loading>
                            <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
                            <p style="color:white">Loading data, please wait...</p>
                        </template>
                        <Column headerStyle="width: 8rem">
                            <template #body="slotProps">
                                <VIconButton type="button" raised circle icon="fas fa-plus"
                                    @click="addTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Pilih'">
                                </VIconButton>
                            </template>
                        </Column>
                        <Column field="namatemplate" header="Nama" :sortable="true"></Column>
                        <Column field="created_at" header="Tanggal" :sortable="true">
                            <template #body="slotProps">
                                <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
                            </template>
                        </Column>
                    </DataTable>
                </template>
            </VModal>


            <div class="columns is-multiline p-2">
                <div class="column is-12 buttons pb-0 mb-0 mt-0" style="margin:10px;vertical-align:middle">
                    <VButton type="button" rounded outlined color="info" raised icon="feather:folder"
                        :loading="isLoading" @click="pilihTemplateFix(index)"> Pilih Template
                    </VButton>
                </div>

                <div class="column is-12 p-0">
                    <hr class="m-0">
                </div>

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

                <div class="column is-12 p-0">
                    <hr class="m-0">
                </div>

                <div class="column is-12 ">
                    <Fieldset :toggleable="true" legend="PRA OPERATIF">
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="DATA UMUM">
                                <div class="column is-12" v-for="(datas) in Pengkajian">
                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                    <div class="columns is-multiline">
                                        <div class="column is-3" v-for="(data) in datas.value">
                                            <VField v-if="data.type == 'checkBox'">
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                                        :label="data.subTitle" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                            <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                style="margin-bottom: 0.5rem;">
                                                <VControl raw subcontrol>
                                                    <input v-model="input[data.model]" class="input p-0" />
                                                </VControl>
                                            </VField>
                                            <VField :label="data.subTitle" v-else-if="data.type == 'datePicker'"
                                                style="margin-bottom: 0.5rem;">
                                                <VDatePicker v-model="input[data.model]" mode="date" trim-weeks
                                                    :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VControl icon="feather:calendar" fullwidth>
                                                            <VInput :value="inputValue" v-on="inputEvents" />
                                                        </VControl>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                            <VField :label="data.subTitle" v-else-if="data.type == 'timePicker'"
                                                style="margin-bottom: 0.5rem;">
                                                <VDatePicker v-model="input[data.model]" mode="time" is24hr>
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VControl icon="feather:clock" fullwidth>
                                                            <VInput :value="inputValue" v-on="inputEvents" />
                                                        </VControl>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                            <VField :label="data.subTitle" v-else-if="data.type == 'textArea'"
                                                style="margin-bottom: 0.5rem;">
                                                <VTextarea rows="2" v-model="input[data.model]"></VTextarea>
                                            </VField>
                                            <VField v-else-if="data.type == 'checkbox'" style="margin-bottom: 0.5rem;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        :true-value="data.subTitle" :label="data.subTitle"
                                                        v-model="input[data.model]" />
                                                </VControl>
                                            </VField>
                                            <VField v-else-if="data.type == 'checkboxText'"
                                                style="margin-bottom: 0.5rem;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square
                                                        :true-value="data.subTitle" :label="data.subTitle"
                                                        v-model="input[data.model]" />
                                                </VControl>
                                                <VControl raw subcontrol>
                                                    <VTextarea rows="2" v-model="input[data.model2]"></VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>

                                </div>
                                <div class="column is-12" v-for="(data) in TimOperasi">
                                    <h1 style="font-weight : bold; margin-bottom: 0.5rem; text-align:center;">{{
                                        data.title }}</h1>
                                    <div class="columns is-multiline">
                                        <div class="column is-4" v-for="(detail) in data.value">
                                            <VField :label="detail.subTitle" v-if="detail.type == 'comboBoxPetugas'">
                                                <VControl class="prime-auto">
                                                    <AutoComplete v-model="input[detail.model]" :suggestions="d_Petugas"
                                                        @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                                </VControl>
                                            </VField>
                                            <VField :label="detail.subTitle" v-if="detail.type == 'comboBoxDokter'">
                                                <VControl class="prime-auto">
                                                    <AutoComplete v-model="input[detail.model]" :suggestions="d_Dokter"
                                                        @complete="fetchDokter($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </Fieldset>
                        </div>
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="PENGKAJIAN (DATA FOKUS)">
                                <div class="column is-12">
                                    <div class="column is-12" v-for="(datas) in DataFokus">
                                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                        <div class="columns is-multiline">
                                            <div class="column is-2" v-for="(data) in datas.value">
                                                <VField :label="data.subTitle" v-if="data.type == 'text'">
                                                </VField>
                                                <VField :label="data.subTitle" v-if="data.type == 'textChoice'">
                                                    <VField addons>
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model]" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>{{ data.nama }}</VButton>
                                                        </VControl>
                                                    </VField>
                                                </VField>
                                                <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                    style="margin-bottom: 0.5rem;">
                                                    <VControl raw subcontrol>
                                                        <input v-model="input[data.model]" class="input p-0" />
                                                    </VControl>
                                                </VField>
                                                <VField v-else-if="data.type == 'checkboxText'"
                                                    style="margin-bottom: 0.5rem;">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle" :label="data.subTitle"
                                                            v-model="input[data.model]" />
                                                    </VControl>
                                                    <VControl raw subcontrol>
                                                        <VTextarea rows="2" v-model="input[data.model2]"></VTextarea>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="columns is-multiline">
                                    <div class="column is-4" v-for="(datas) in DataObyektif">
                                        <h1 style="font-weight: bold;">{{ datas.title }}</h1>
                                        <div class="columns is-multiline">
                                            <div class="column is-6" v-for="(data) in datas.value">
                                                <VField :label="data.subTitle" v-if="data.type == 'text'">
                                                </VField>
                                                <VField v-else-if="data.type == 'checkboxBebas'"
                                                    style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                    <VControl raw subcontrol
                                                        style="display: flex; align-items: center;">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle" :label="data.subTitle"
                                                            v-model="input[data.model]" />
                                                        <VInput type="text" class="input"
                                                            v-model="input[data.model2]" />
                                                    </VControl>
                                                </VField>
                                                <VField v-else-if="data.type == 'checkbox'"
                                                    style="margin-bottom: 0.5rem;">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle" :label="data.subTitle"
                                                            v-model="input[data.model]" />
                                                    </VControl>
                                                </VField>
                                                <VField v-else-if="data.type == 'checkboxGCS'"
                                                    style="margin-bottom: 0.5rem;">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle" :label="data.subTitle"
                                                            v-model="input[data.model]" />
                                                    </VControl>
                                                    <VField addons>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>{{ data.nama }}</VButton>
                                                        </VControl>
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model2]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField addons>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>{{ data.nama2 }}</VButton>
                                                        </VControl>
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model3]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField addons>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>{{ data.nama3 }}</VButton>
                                                        </VControl>
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model4]" />
                                                        </VControl>
                                                    </VField>
                                                </VField>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="column is-4">
                                        <VField label="Data penunjang laboratorium">
                                            <VTextarea rows="2" v-model="input.dataPenunjangLaboratorium_"></VTextarea>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField label="Radiologi">
                                            <VTextarea rows="2" v-model="input.radiologi_"></VTextarea>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField label="Data penunjang lainnya">
                                            <VTextarea rows="2" v-model="input.dataPenunjangLainnya_"></VTextarea>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField label="Keterangan lain">
                                            <VTextarea rows="2" v-model="input.keteranganLain_"></VTextarea>
                                        </VField>
                                    </div>
                                </div>

                                <table class="table is-fullwidth is-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center; width: 50%;">Diagnosa Keperawatan</th>
                                            <th style="text-align: center; width: 50%;">Rencana Keperawatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(datas, index) in diagnosaKeperawatan" :key="'diagnosa-' + index">
                                            <td>
                                                <div v-for="(data) in datas.value" :key="data.id">
                                                    <VField v-if="data.type == 'checkboxBebas'"
                                                        style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                        <VControl raw subcontrol
                                                            style="display: flex; align-items: center;">
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                            <VTextarea rows="4" v-model="input[data.model2]">
                                                            </VTextarea>
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'checkbox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'textBox'" style="display: flex;">
                                                        <span>- &nbsp;&nbsp;</span>
                                                        <VField>
                                                            <VTextarea rows="2" v-model="input[data.model]"></VTextarea>
                                                        </VField>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'text'" style="display: flex;">
                                                        <span>- {{ data.subTitle }}</span>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'textarea'">
                                                        <VField :label="data.subTitle">
                                                            <VTextarea rows="2" v-model="input[data.model]"></VTextarea>
                                                        </VField>
                                                    </VField>
                                                </div>
                                            </td>
                                            <td v-if="rencanaKeperawatan[index]">
                                                <div v-for="(data) in rencanaKeperawatan[index].value" :key="data.id">
                                                    <VField v-if="data.type == 'checkboxBebas'"
                                                        style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                        <VControl raw subcontrol
                                                            style="display: flex; align-items: center;">
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model2]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'checkbox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" @change="changeAll"/>
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'textBox'" style="display: flex;">
                                                        <span>-</span>
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'text'" style="display: flex;">
                                                        <span>- {{ data.subTitle }}</span>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'textarea'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VField :label="data.subTitle">
                                                            <VTextarea rows="2" v-model="input[data.model]"></VTextarea>
                                                        </VField>
                                                    </VField>
                                                </div>
                                            </td>
                                            <td v-else></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table is-fullwidth is-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center; width: 50%;">Tindakan Keperawatan</th>
                                            <th style="text-align: center; width: 50%;" colspan="2">Evaluasi Keperawatan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(datas, index) in tindakanKeperawatan" :key="'tindakan-' + index">
                                            <!-- Tindakan Keperawatan -->
                                            <td>
                                                <div v-for="(data) in datas.value" :key="data.id">
                                                    <VField v-if="data.type == 'checkbox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'checkboxText'"
                                                        style="margin-bottom: 0.5rem; display:flex;">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]"
                                                                style="margin-top: 12px; padding-right: 4px;" />
                                                        </VControl>
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model2]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'checkboxLancar'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <!-- Checkbox field with subTitle as label -->
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]"
                                                                style="margin-top: 12px; padding-right: 4px;" />
                                                        </VControl>

                                                        <!-- Text Input fields for Lokasi, Ukuran, and Nama Pemasang with appropriate labels -->
                                                        <div style="">
                                                            <VField label="Lokasi :" style="margin-top: 12px;">
                                                            </VField>
                                                            <VControl raw subcontrol>
                                                                <VInput type="text" class="input"
                                                                    v-model="input[data.model2]" />
                                                            </VControl>
                                                        </div>

                                                        <div style="">
                                                            <VField label="Ukuran :" style="margin-top: 12px;">
                                                            </VField>
                                                            <VControl raw subcontrol>
                                                                <VInput type="text" class="input"
                                                                    v-model="input[data.model3]" />
                                                            </VControl>
                                                        </div>

                                                        <VField label="Nama Pemasang :">
                                                            <VControl raw subcontrol>
                                                                <VInput type="text" class="input"
                                                                    v-model="input[data.model4]" />
                                                            </VControl>
                                                        </VField>
                                                    </VField>

                                                </div>
                                            </td>

                                            <!-- Evaluasi Keperawatan Part 1 -->
                                            <td v-if="evaluasiKeperawatan[index]" style="width: 22%;">
                                                <div v-for="(data, dataIndex) in evaluasiKeperawatan[index].value"
                                                    :key="'eval-part1-' + data.id">
                                                    <div v-if="data.column == 1">
                                                        <VField v-if="data.type == 'checkbox'"
                                                            style="margin-bottom: 0.5rem;">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'checkboxText'"
                                                            style="margin-bottom: 0.5rem;">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                            </VControl>
                                                            <VControl raw subcontrol>
                                                                <VInput type="text" class="input"
                                                                    v-model="input[data.model2]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'checkboxText2'"
                                                            style="margin-bottom: 0.5rem;">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]"
                                                                    style="margin-top: 12px; padding-right: 4px;" />
                                                            </VControl>
                                                            <VControl raw subcontrol>
                                                                <VInput type="text" class="input"
                                                                    v-model="input[data.model2]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'checkboxText3'"
                                                            style="margin-bottom: 0.5rem;">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]"
                                                                    style="margin-top: 12px; padding-right: 4px;" />
                                                            </VControl>
                                                            <VField label="Jenis :">
                                                                <VControl raw subcontrol>
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[data.model2]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField label="Jam :">
                                                                <VControl raw subcontrol>
                                                                    <VDatePicker v-model="input[data.model3]"
                                                                        mode="time" is24hr>
                                                                        <template
                                                                            #default="{ inputValue, inputEvents }">
                                                                            <VControl icon="feather:clock" fullwidth>
                                                                                <VInput :value="inputValue"
                                                                                    v-on="inputEvents" />
                                                                            </VControl>
                                                                        </template>
                                                                    </VDatePicker>
                                                                </VControl>
                                                            </VField>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'checkboxLancar'"
                                                            style="margin-bottom: 0.5rem;">
                                                            <!-- Checkbox field with subTitle as label -->
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]"
                                                                    style="margin-top: 12px; padding-right: 4px;" />
                                                            </VControl>

                                                            <!-- Text Input fields for Lokasi, Ukuran, and Nama Pemasang with appropriate labels -->
                                                            <div style="">
                                                                <VField label="Lokasi :" style="margin-top: 12px;">
                                                                </VField>
                                                                <VControl raw subcontrol>
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[data.model2]" />
                                                                </VControl>
                                                            </div>

                                                            <div style="">
                                                                <VField label="Ukuran :" style="margin-top: 12px;">
                                                                </VField>
                                                                <VControl raw subcontrol>
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[data.model3]" />
                                                                </VControl>
                                                            </div>

                                                            <VField label="Nama Pemasang :">
                                                                <VControl raw subcontrol>
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[data.model4]" />
                                                                </VControl>
                                                            </VField>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Evaluasi Keperawatan Part 2 -->
                                            <td v-if="evaluasiKeperawatan[index]">
                                                <div v-for="(data, dataIndex) in evaluasiKeperawatan[index].value"
                                                    :key="'eval-part2-' + data.id">
                                                    <div v-if="data.column == 2">
                                                        <VField v-if="data.type == 'checkbox'"
                                                            style="margin-bottom: 0.5rem;">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'checkboxText'"
                                                            style="margin-bottom: 0.5rem; display:flex;">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0 mt-3" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                            </VControl>
                                                            <VControl raw subcontrol>
                                                                <VInput type="text" style="width: 90%;"
                                                                    class="input ml-4" v-model="input[data.model2]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'checkboxLancar'"
                                                            style="margin-bottom: 0.5rem;">
                                                            <!-- Checkbox field with subTitle as label -->
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]"
                                                                    style="margin-top: 12px; padding-right: 4px;" />
                                                            </VControl>

                                                            <!-- Text Input fields for Lokasi, Ukuran, and Nama Pemasang with appropriate labels -->
                                                            <div style="">
                                                                <VField label="Lokasi :" style="margin-top: 12px;">
                                                                </VField>
                                                                <VControl raw subcontrol>
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[data.model2]" />
                                                                </VControl>
                                                            </div>

                                                            <div style="">
                                                                <VField label="Ukuran :" style="margin-top: 12px;">
                                                                </VField>
                                                                <VControl raw subcontrol>
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[data.model3]" />
                                                                </VControl>
                                                            </div>

                                                            <VField label="Nama Pemasang :">
                                                                <VControl raw subcontrol>
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[data.model4]" />
                                                                </VControl>
                                                            </VField>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Empty Cells for Evaluasi Keperawatan -->
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <VField label="Keterangan Lain">
                                                    <VTextarea rows="2" v-model="input.keteranganKeperawatan">
                                                    </VTextarea>
                                                </VField>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1">
                                                <VField label="Nama Perawat">
                                                    <VControl class="prime-auto">
                                                        <AutoComplete v-model="input.perawatKeperawatan"
                                                            :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                            :field="'label'" class="mt-2" />
                                                    </VControl>
                                                </VField>
                                            </td>
                                            <td colspan="2">
                                                <VField label="Tanda tangan">
                                                    <TandaTangan :elemenID="'signature_1'" :width="'150'"
                                                        :height="'150'" class="dek" />
                                                </VField>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </Fieldset>
                        </div>
                    </Fieldset>
                </div>

                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="INTRA OPERATIF">
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="PENGKAJIAN (DATA DASAR)">

                                <div class="columns is-multiline">
                                    <div class="column is-half">
                                        <h1 style="font-weight: bold;">Data subyektif :</h1>
                                        <VField>
                                            <VControl>
                                                <VTextarea class="textarea" v-model="input.dataSubyektif_" rows="2"
                                                    placeholder="Data Subyektif" autocomplete="off" autocapitalize="off"
                                                    spellcheck="true" />
                                            </VControl>
                                        </VField>
                                        <h1 style="font-weight: bold;">Data obyektif :</h1>
                                        <VField>
                                            <VControl>
                                                <VTextarea class="textarea" v-model="input.dataObyektif_" rows="2"
                                                    placeholder="Data Obyektif" autocomplete="off" autocapitalize="off"
                                                    spellcheck="true" />
                                            </VControl>
                                        </VField>
                                        <VField>
                                            <VControl>
                                                <label style="padding-right: 4px;">Suhu OK :</label>
                                            </VControl>
                                        </VField>
                                        <VField addons>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.dOSuhu_" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>°C</VButton>
                                            </VControl>
                                        </VField>
                                        <VField>
                                            <VControl>
                                                <label style="padding-right: 4px;">Kelembaban OK :</label>
                                            </VControl>
                                        </VField>
                                        <VField addons>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.dOKelembabanOK_" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>%</VButton>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-half">
                                        <div class="column is-12" v-for="(datas) in kondisiPasien">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-4" v-for="(data) in datas.value">
                                                    <VField addons :label="data.subTitle"
                                                        v-if="data.type == 'textBoxChoice'">
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model]" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>{{ data.nama }}</VButton>
                                                        </VControl>
                                                    </VField>
                                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <input v-model="input[data.model]" class="input p-0" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="columns is-multiline">
                                    <div class="column is-half">
                                        <div class="column is-12" v-for="(datas) in setInstrumen">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-6" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkboxBebas'"
                                                        style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                        <VControl raw subcontrol
                                                            style="display: flex; align-items: center;">
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model2]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'checkbox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'checkboxText'"
                                                        style="margin-bottom: 0.5rem; display: flex;">
                                                        <VControl raw subcontrol style="width: 50%;">
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                        </VControl>
                                                        <VControl raw subcontrol style="width: 50%;">
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model2]" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-half">
                                        <div class="columns is-half">
                                            <div class="column is-6" v-for="(datas) in alatLain">
                                                <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}
                                                </h1>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12" v-for="(data) in datas.value">
                                                        <VField v-if="data.type == 'checkboxBebas'"
                                                            style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                            <VControl raw subcontrol
                                                                style="display: flex; align-items: center;">
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                                <VInput type="text" class="input"
                                                                    v-model="input[data.model2]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'checkbox'"
                                                            style="margin-bottom: 0.5rem;">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'checkboxText'"
                                                            style="margin-bottom: 0.5rem; display: flex;">
                                                            <VControl raw subcontrol style="width: 50%;">
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                            </VControl>
                                                            <VControl raw subcontrol style="width: 50%;">
                                                                <VInput type="text" class="input"
                                                                    v-model="input[data.model2]" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="column is-6" v-for="(datas) in jenisAnestesi">
                                                <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}
                                                </h1>
                                                <div class="columns is-multiline">
                                                    <div class="column is-12" v-for="(data) in datas.value">
                                                        <VField v-if="data.type == 'checkboxBebas'"
                                                            style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                            <VControl raw subcontrol
                                                                style="display: flex; align-items: center;">
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                                <VInput type="text" class="input"
                                                                    v-model="input[data.model2]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'checkbox'"
                                                            style="margin-bottom: 0.5rem;">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'checkboxText'"
                                                            style="margin-bottom: 0.5rem; display: flex;">
                                                            <VControl raw subcontrol style="width: 30%;">
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                            </VControl>
                                                            <VControl raw subcontrol style="width: 70%;">
                                                                <VInput type="text" class="input"
                                                                    v-model="input[data.model2]" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="columns is-multiline">
                                    <div class="column is-3" v-for="(datas) in iOBreath">
                                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                        <div class="columns is-multiline">
                                            <div class="column is-12" v-for="(data) in datas.value">
                                                <VField v-if="data.type == 'checkboxBebas'"
                                                    style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                    <VControl raw subcontrol
                                                        style="display: flex; align-items: center;">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle" :label="data.subTitle"
                                                            v-model="input[data.model]" />
                                                        <VInput type="text" class="input"
                                                            v-model="input[data.model2]" />
                                                    </VControl>
                                                </VField>
                                                <VField v-else-if="data.type == 'DBcheckboxBebas'"
                                                    style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                    <VControl raw subcontrol style="width:50%">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle" :label="data.subTitle"
                                                            v-model="input[data.model]" />
                                                    </VControl>
                                                    <VControl raw subcontrol
                                                        style="display: flex; align-items: center; width:50%">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle2" :label="data.subTitle2"
                                                            v-model="input[data.model2]" />
                                                        <VInput type="text" class="input"
                                                            v-model="input[data.model3]" />
                                                    </VControl>
                                                </VField>
                                                <VField v-else-if="data.type == 'checkboxBebas'"
                                                    style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                    <VControl raw subcontrol
                                                        style="display: flex; align-items: center;">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle" :label="data.subTitle"
                                                            v-model="input[data.model]" />
                                                        <VInput type="text" class="input"
                                                            v-model="input[data.model2]" />
                                                    </VControl>
                                                </VField>
                                                <VField v-else-if="data.type == 'checkbox'"
                                                    style="margin-bottom: 0.5rem;">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle" :label="data.subTitle"
                                                            v-model="input[data.model]" />
                                                    </VControl>
                                                </VField>
                                                <VField v-else-if="data.type == 'DBcheckbox'"
                                                    style="margin-bottom: 0.5rem; display: flex">
                                                    <VControl raw subcontrol style="width:50%">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle" :label="data.subTitle"
                                                            v-model="input[data.model]" />
                                                    </VControl>
                                                    <VControl raw subcontrol style="width:50%">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle2" :label="data.subTitle2"
                                                            v-model="input[data.model2]" />
                                                    </VControl>
                                                </VField>
                                                <VField v-else-if="data.type == 'checkboxText'"
                                                    style="margin-bottom: 0.5rem; display: flex;">
                                                    <VControl raw subcontrol style="width: 50%;">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle" :label="data.subTitle"
                                                            v-model="input[data.model]" />
                                                    </VControl>
                                                    <VControl raw subcontrol style="width: 50%;">
                                                        <VInput type="text" class="input"
                                                            v-model="input[data.model2]" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="columns is-multiline">
                                    <div class="column is-half">
                                        <VField>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input.pemasanganEvaluasiKateterUrin_"
                                                    :true-value="'Pemasangan/evaluasi kateter urin'"
                                                    label="Pemasangan/evaluasi kateter urin" class="p-0" color="primary"
                                                    square />
                                            </VControl>
                                            <VControl style="display: flex;">
                                                <VField label="Ukuran :" style="padding-right: 6px; padding-top: 6px;">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.ukuranPemasanganEvaluasiKateterUrin_" />
                                                    </VControl>
                                                </VField>
                                                <VField label="Nama pemasang :">
                                                    <VControl class="prime-auto">
                                                        <AutoComplete v-model="input.namaPemasanganEvaluasiKateterUrin"
                                                            :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                            :field="'label'" class="mt-2" />
                                                    </VControl>
                                                </VField>
                                            </VControl>
                                        </VField>
                                        <VField>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input.ngt_" :true-value="'NGT'" label="NGT"
                                                    class="p-0" color="primary" square />
                                            </VControl>
                                            <VControl style="display: flex;">
                                                <VField label="No :" style="padding-right: 6px; padding-top: 6px;">
                                                    <VControl>
                                                        <VInput type="text" class="input" v-model="input.noNGT_" />
                                                    </VControl>
                                                </VField>
                                                <VField label="Nama pemasang :">
                                                    <VControl class="prime-auto">
                                                        <AutoComplete v-model="input.namaNGT" :suggestions="d_Petugas"
                                                            @complete="fetchPetugas($event)" :optionLabel="'label'"
                                                            :dropdown="true" :minLength="3" :appendTo="'body'"
                                                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                            class="mt-2" />
                                                    </VControl>
                                                </VField>
                                            </VControl>
                                        </VField>
                                        <VField>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input.packingTenggorokan_"
                                                    :true-value="'Packing Tenggorokan. Jam dikeluarkan :'"
                                                    label="Packing Tenggorokan. Jam dikeluarkan :" class="p-0"
                                                    color="primary" square />
                                                <VDatePicker v-model="input.jamPackingTenggorokan_" mode="time" is24hr>
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VControl icon="feather:clock" fullwidth>
                                                            <VInput :value="inputValue" v-on="inputEvents" />
                                                        </VControl>
                                                    </template>
                                                </VDatePicker>
                                            </VControl>
                                        </VField>
                                        <VField>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input.penggunaanTorniquet_"
                                                    :true-value="'Penggunaan torniquet'" label="Penggunaan torniquet"
                                                    class="p-0" color="primary" square />
                                            </VControl>
                                        </VField>
                                        <div class="columns is-multiline">
                                            <div class="column is-half">
                                                <VField label="Lokasi :">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.torniquetLokasi" />
                                                    </VControl>
                                                </VField>
                                                <VField label="Tekanan :">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input.torniquetTekanan" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-half">
                                                <VField label="Jam mulai :">
                                                    <VDatePicker v-model="input.torniquetJamMulai" mode="time" is24hr>
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VControl icon="feather:clock" fullwidth>
                                                                <VInput :value="inputValue" v-on="inputEvents" />
                                                            </VControl>
                                                        </template>
                                                    </VDatePicker>
                                                </VField>
                                                <VField label="Jam selesai :">
                                                    <VDatePicker v-model="input.torniquetJamSelesai" mode="time" is24hr>
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VControl icon="feather:clock" fullwidth>
                                                                <VInput :value="inputValue" v-on="inputEvents" />
                                                            </VControl>
                                                        </template>
                                                    </VDatePicker>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-half">
                                        <h1 style="font-weight: bold; margin-bottom: 1rem;">Diatermy</h1>
                                        <div class="columns is-multiline">
                                            <div class="column is-12">
                                                <VField style="display: flex;">
                                                    <VControl raw subcontrol style="width: 50%;">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="Bipolar" label="Bipolar"
                                                            v-model="input.diatermyBipolar_" />
                                                    </VControl>
                                                    <VControl style="width: 50%;">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="Monopolar" label="Monopolar"
                                                            v-model="input.diatermyMonopolar_" />
                                                    </VControl>
                                                </VField>
                                                <VField>
                                                    <span>Tempat pemasangan arde plat</span>
                                                </VField>
                                            </div>
                                            <div class="column is-12">
                                                <ImgDraw elemenID="Gambar1" height="250" width="250"
                                                    imageSrc="/images/simrs/outline-only-body.jpg" />
                                            </div>
                                            <div class="column is-12">
                                                <h1 style="font-weight: bold; margin-bottom: 1rem;">Alat bantu posisi
                                                    pasien</h1>
                                                <VField style="display: flex;">
                                                    <VControl raw subcontrol style="width: 50%;">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="LateralSupport" label="Lateral Support"
                                                            v-model="input.alatBantuPosisiPasienLateralSupport_" />
                                                    </VControl>
                                                    <VControl style="width: 50%;">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="HeadRing" label="Head ring"
                                                            v-model="input.alatBantuPosisiPasienHeadRing_" />
                                                    </VControl>
                                                </VField>
                                                <VField style="display: flex;">
                                                    <VControl raw subcontrol style="width: 50%;">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="Bantal" label="Bantal"
                                                            v-model="input.alatBantuPosisiPasienBantal_" />
                                                    </VControl>
                                                    <VControl style="width: 50%;">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="Stirups" label="Stirups"
                                                            v-model="input.alatBantuPosisiPasienStirups_" />
                                                    </VControl>
                                                </VField>
                                                <VField style="display: flex;">
                                                    <VControl raw subcontrol style="width: 50%;">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="ArmBoard" label="Arm board"
                                                            v-model="input.alatBantuPosisiPasienArmBoard_" />
                                                    </VControl>
                                                    <VControl style="width: 50%; display:flex;">
                                                        <VCheckbox class="p-0 mt-3" color="primary" square
                                                            :true-value="Bebas" label=""
                                                            v-model="input.alatBantuPosisiPasienBebas_" />
                                                        <VControl>
                                                            <VInput type="text" class="input"
                                                                v-model="input.alatBantuPosisiPasienBebasText_" />
                                                        </VControl>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-12">
                                        <table class="table is-fullwidth is-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center; width: 50%;">Diagnosa Keperawatan
                                                    </th>
                                                    <th style="text-align: center; width: 50%;">Rencana Keperawatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(datas, index) in diagnosaKeperawatan2"
                                                    :key="'diagnosa-' + index">
                                                    <td>
                                                        <div v-for="(data) in datas.value" :key="data.id">
                                                            <VField v-if="data.type == 'checkboxBebas'"
                                                                style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                                <VControl raw subcontrol
                                                                    style="display: flex; align-items: center;">
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                    <VTextarea rows="4" v-model="input[data.model2]">
                                                                    </VTextarea>
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'checkbox'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textBox'"
                                                                style="display: flex;">
                                                                <span>- &nbsp;&nbsp;</span>
                                                                <VField>
                                                                    <VTextarea rows="2" v-model="input[data.model]">
                                                                    </VTextarea>
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textBox2'"
                                                                style="display: flex;">
                                                                <span>- &nbsp;&nbsp;</span>
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input[data.model]" />
                                                                    </VControl>
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'text'"
                                                                style="display: flex;">
                                                                <span>- {{ data.subTitle }}</span>
                                                            </VField>
                                                            <VField :label="data.subTitle"
                                                                v-else-if="data.type == 'combo'">
                                                                <VControl class="prime-auto">
                                                                    <AutoComplete v-model="input[data.model]"
                                                                        :suggestions="d_Petugas"
                                                                        @complete="fetchPetugas($event)"
                                                                        :optionLabel="'label'" :dropdown="true"
                                                                        :minLength="3" :appendTo="'body'"
                                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                        class="mt-2" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textarea'">
                                                                <VField :label="data.subTitle">
                                                                    <VTextarea rows="2" v-model="input[data.model]">
                                                                    </VTextarea>
                                                                </VField>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td v-if="rencanaKeperawatan2[index]">
                                                        <div v-for="(data) in rencanaKeperawatan2[index].value"
                                                            :key="data.id">
                                                            <VField v-if="data.type == 'checkboxBebas'"
                                                                style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                                <VControl raw subcontrol
                                                                    style="display: flex; align-items: center;">
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[data.model2]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'checkbox'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'ttd'">
                                                                <VField>
                                                                    <span>{{ data.subTitle }}</span>
                                                                </VField>
                                                                <VField>
                                                                    <TandaTangan v-model="data.model"
                                                                        :elemenID="data.model" :width="'150'"
                                                                        :height="'150'" class="dek" />
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textBox'"
                                                                style="display: flex;">
                                                                <span>-</span>
                                                                <VControl>
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[data.model]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'text'"
                                                                style="display: flex;">
                                                                <span>- {{ data.subTitle }}</span>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textarea'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <VField :label="data.subTitle">
                                                                    <VTextarea rows="2" v-model="input[data.model]">
                                                                    </VTextarea>
                                                                </VField>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td v-else></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="column is-12">
                                        <table class="table is-fullwidth is-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center; width: 50%;">Tindakan Keperawatan
                                                    </th>
                                                    <th style="text-align: center; width: 50%;">Evaluasi Keperawatan
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(datas, index) in tindakanKeperawatan2"
                                                    :key="'diagnosa-' + index">
                                                    <td>
                                                        <div v-for="(data) in datas.value" :key="data.id">
                                                            <VField v-if="data.type == 'checkboxBebas'"
                                                                style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                                <VControl raw subcontrol
                                                                    style="display: flex; align-items: center;">
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                    <VTextarea rows="4" v-model="input[data.model2]">
                                                                    </VTextarea>
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'checkbox'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textBox'"
                                                                style="display: flex;">
                                                                <span>- &nbsp;&nbsp;</span>
                                                                <VField>
                                                                    <VTextarea rows="2" v-model="input[data.model]">
                                                                    </VTextarea>
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textBox2'"
                                                                style="display: flex;">
                                                                <span>- &nbsp;&nbsp;</span>
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input[data.model]" />
                                                                    </VControl>
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'text'"
                                                                style="display: flex;">
                                                                <span>- {{ data.subTitle }}</span>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual1'"
                                                                style="display: flex;">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td>
                                                                            <VField style="margin-bottom: 0.5rem;">
                                                                                <VControl raw subcontrol>
                                                                                    <VCheckbox class="p-0"
                                                                                        color="primary" square
                                                                                        true-value="KolaborasiPencucianLuka"
                                                                                        label="Kolaborasi Pencucian Luka"
                                                                                        v-model="input.tK2KolaborasiPencucianLuka" />
                                                                                </VControl>
                                                                            </VField>
                                                                        </td>
                                                                        <td>
                                                                            <VField style="margin-bottom: 0.5rem;">
                                                                                <span>Jenis cairan yang digunakan</span>
                                                                            </VField>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <VField style="margin-bottom: 0.5rem;">
                                                                            </VField>
                                                                        </td>
                                                                        <td>
                                                                            <VField>
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.tK2JenisCairanText1" />
                                                                                </VControl>
                                                                            </VField>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <VField style="margin-bottom: 0.5rem;">
                                                                            </VField>
                                                                        </td>
                                                                        <td>
                                                                            <VField>
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.tK2JenisCairanText2" />
                                                                                </VControl>
                                                                            </VField>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual2'">
                                                                <VField label="Ukuran drain no :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eK2UkuranDrainNoText" />
                                                                    </VControl>
                                                                </VField>
                                                                <VField label="Lokasi drain :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eK2LokasiDrainText" />
                                                                    </VControl>
                                                                </VField>
                                                            </VField>
                                                            <VField :label="data.subTitle"
                                                                v-else-if="data.type == 'combo'">
                                                                <VControl class="prime-auto">
                                                                    <AutoComplete v-model="input[data.model]"
                                                                        :suggestions="d_Petugas"
                                                                        @complete="fetchPetugas($event)"
                                                                        :optionLabel="'label'" :dropdown="true"
                                                                        :minLength="3" :appendTo="'body'"
                                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                        class="mt-2" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textarea'">
                                                                <VField :label="data.subTitle">
                                                                    <VTextarea rows="2" v-model="input[data.model]">
                                                                    </VTextarea>
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'text2'"
                                                                style="display: flex;">
                                                                <span style="text-align: center;">{{ data.subTitle
                                                                    }}</span>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td v-if="evaluasiKeperawatan2[index]">
                                                        <div v-for="(data) in evaluasiKeperawatan2[index].value"
                                                            :key="data.id">
                                                            <VField v-if="data.type == 'checkboxBebas'"
                                                                style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                                <VControl raw subcontrol
                                                                    style="display: flex; align-items: center;">
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[data.model2]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'checkbox'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'ttd'">
                                                                <VField>
                                                                    <span>{{ data.subTitle }}</span>
                                                                </VField>
                                                                <VField>
                                                                    <TandaTangan v-model="data.model"
                                                                        :elemenID="data.model" :width="'150'"
                                                                        :height="'150'" class="dek" />
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textBox'"
                                                                style="display: flex;">
                                                                <span>-</span>
                                                                <VControl>
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[data.model]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'text'"
                                                                style="display: flex;">
                                                                <span>- {{ data.subTitle }}</span>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'text2'"
                                                                style="display: flex;">
                                                                <span style="text-align: center;">{{ data.subTitle
                                                                    }}</span>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual1'">
                                                                <VField>
                                                                    <span>Preparation solution yang digunakan :</span>
                                                                </VField>
                                                                <VField style="display: flex;">
                                                                    <VControl raw subcontrol style="width: 50%;">
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="PovidoneIodine"
                                                                            label="Povidone iodine"
                                                                            v-model="input.eK2PovidoneIodine" />
                                                                    </VControl>
                                                                    <VControl raw subcontrol style="width: 50%;">
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="Alkohol" label="Alkohol"
                                                                            v-model="input.eK2Alkohol" />
                                                                    </VControl>
                                                                </VField>
                                                                <VField style="display: flex;">
                                                                    <VControl raw subcontrol style="width: 50%;">
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="ChlorhexidineAlcohol"
                                                                            label="Chlorhexidine alcohol"
                                                                            v-model="input.eK2ChlorhexidineAlcohol" />
                                                                    </VControl>
                                                                    <VControl raw subcontrol style="width: 50%;">
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="Lainnya" label="Lainnya"
                                                                            v-model="input.eK2Lainnya" />
                                                                    </VControl>
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual2'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square
                                                                                    true-value="DiatermiBerfungsiBaik"
                                                                                    label="Diatermi Berfungsi Baik"
                                                                                    v-model="input.eK2DiatermiBerfungsiBaik" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square true-value="TidakDiperlukan"
                                                                                    label="Tidak Diperlukan"
                                                                                    v-model="input.eK2TidakDiperlukan1" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual3'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square
                                                                                    true-value="TerpasangDanTermonitor"
                                                                                    label="Terpasang Dan Termonitor"
                                                                                    v-model="input.eK2TerpasangDanTermonitor" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square true-value="TidakDiperlukan"
                                                                                    label="Tidak Diperlukan"
                                                                                    v-model="input.eK2TidakDiperlukan2" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual4'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td>
                                                                            <span style="text-align: center;">Nama
                                                                                obat</span>
                                                                        </td>
                                                                        <td>
                                                                            <span
                                                                                style="text-align: center;">Lokasi</span>
                                                                        </td>
                                                                        <td>
                                                                            <span style="text-align: center;">Total
                                                                                dosis</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2NamaObat1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2Lokasi1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2TotalDosis1" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2NamaObat2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2Lokasi2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2TotalDosis2" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual5'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <span style="text-align: center;">Kondisi
                                                                                hangat</span>
                                                                        </td>
                                                                        <td>
                                                                            <span style="text-align: center;">Total
                                                                                volume</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square :true-value="Ya" label="Ya"
                                                                                    v-model="input.tK2KondisiHangatYa1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square :true-value="Tidak"
                                                                                    label="Tidak"
                                                                                    v-model="input.tK2KondisiHangatTidak1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VField addons>
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.tK2KondisiHangatText1" />
                                                                                </VControl>
                                                                                <VControl class="field-addon-body">
                                                                                    <VButton static>ml</VButton>
                                                                                </VControl>
                                                                            </VField>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square :true-value="Ya" label="Ya"
                                                                                    v-model="input.tK2KondisiHangatYa2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square :true-value="Tidak"
                                                                                    label="Tidak"
                                                                                    v-model="input.tK2KondisiHangatTidak2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VField addons>
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.tK2KondisiHangatText2" />
                                                                                </VControl>
                                                                                <VControl class="field-addon-body">
                                                                                    <VButton static>ml</VButton>
                                                                                </VControl>
                                                                            </VField>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual6'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td style="width: 30%;">
                                                                            <span style="text-align: center;">Lokasi
                                                                                Luka</span>
                                                                        </td>
                                                                        <td style="width: 70%;">
                                                                            <span style="text-align: center;">Tipe
                                                                                dressing</span>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual7'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td style="width: 30%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas1" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square true-value="TulleGrass"
                                                                                    label="Tulle grass"
                                                                                    v-model="input.eK2TulleGrass1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas2" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText2" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas3" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText3" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square true-value="TulleGrass"
                                                                                    label="Tulle grass"
                                                                                    v-model="input.eK2TulleGrass2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas4" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText4" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas5" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText5" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas6" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText6" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas7" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText7" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual8'">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="Utuh" label="Utuh"
                                                                            v-model="input.eK2Utuh" />
                                                                    </VControl>
                                                                </VField>
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="Tidak" label="Tidak, jelaskan"
                                                                            v-model="input.eK2TidakJelaskan" />
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eK2TidakJelaskanText" />
                                                                    </VControl>
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual9'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <th style="width: 10%;">NO</th>
                                                                        <th style="width: 40%;">NAMA BAHAN</th>
                                                                        <th style="width: 50%;">TIPE FIKSASI</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 10%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eKNomorBahan1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2NamaBahan1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 50%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2TipeFiksasi1" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 10%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eKNomorBahan2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2NamaBahan2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 50%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2TipeFiksasi2" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 10%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eKNomorBahan3" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2NamaBahan3" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 50%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2TipeFiksasi3" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual10'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td style="width: 50%;">
                                                                            <VField label="KU :">
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.eK2KUText" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField label="TD :">
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.eK2TDText" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField label="Nadi :">
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.eK2NadiText" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField label="Jumlah cairan infus :">
                                                                                <VField addons>
                                                                                    <VControl>
                                                                                        <VInput type="text"
                                                                                            class="input"
                                                                                            v-model="input.eK2JumlahCairanInfusText" />
                                                                                    </VControl>
                                                                                    <VControl class="field-addon-body">
                                                                                        <VButton static>cc</VButton>
                                                                                    </VControl>
                                                                                </VField>
                                                                            </VField>
                                                                            <VField label="Jumlah transfusi :">
                                                                                <VField addons>
                                                                                    <VControl>
                                                                                        <VInput type="text"
                                                                                            class="input"
                                                                                            v-model="input.eK2JumlahTransfusiText" />
                                                                                    </VControl>
                                                                                    <VControl class="field-addon-body">
                                                                                        <VButton static>cc</VButton>
                                                                                    </VControl>
                                                                                </VField>
                                                                            </VField>
                                                                        </td>
                                                                        <td style="width: 50%;">
                                                                            <VField label="Respirasi :">
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.eK2RespirasiText" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField label="Suhu :">
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.eK2SuhuText" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField label="Saturasi :">
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.eK2SaturasiText" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField label="Jumlah perdarahan :">
                                                                                <VField addons>
                                                                                    <VControl>
                                                                                        <VInput type="text"
                                                                                            class="input"
                                                                                            v-model="input.eK2JumlahPerdarahanText" />
                                                                                    </VControl>
                                                                                    <VControl class="field-addon-body">
                                                                                        <VButton static>cc</VButton>
                                                                                    </VControl>
                                                                                </VField>
                                                                            </VField>
                                                                            <VField label="Jumlah urine :">
                                                                                <VField addons>
                                                                                    <VControl>
                                                                                        <VInput type="text"
                                                                                            class="input"
                                                                                            v-model="input.eK2JumlahUrineText" />
                                                                                    </VControl>
                                                                                    <VControl class="field-addon-body">
                                                                                        <VButton static>cc</VButton>
                                                                                    </VControl>
                                                                                </VField>
                                                                            </VField>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textarea'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <VField :label="data.subTitle">
                                                                    <VTextarea rows="2" v-model="input[data.model]">
                                                                    </VTextarea>
                                                                </VField>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td v-else></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span>KETERANGAN TAMBAHAN</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <VField>
                                                            <VTextarea rows="2" v-model="input.keteranganTambahanText3">
                                                            </VTextarea>
                                                        </VField>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table class="table is-fullwidth is-bordered">
                                                            <tr>
                                                                <td>
                                                                    <span style="text-align: center;">PERAWAT
                                                                        ANESTESI</span>
                                                                </td>
                                                                <td>
                                                                    <span style="text-align: center;">PERAWAT
                                                                        INSTRUMEN</span>
                                                                </td>
                                                                <td>
                                                                    <span style="text-align: center;">PERAWAT
                                                                        SIRKULER</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <VField label="Nama :">
                                                                        <VControl>
                                                                            <VInput type="text" class="input"
                                                                                v-model="input.namaPerawatAnestesiText" />
                                                                        </VControl>
                                                                    </VField>
                                                                </td>
                                                                <td>
                                                                    <VField label="Nama :">
                                                                        <VControl>
                                                                            <VInput type="text" class="input"
                                                                                v-model="input.namaPerawatInstrumenText" />
                                                                        </VControl>
                                                                    </VField>
                                                                </td>
                                                                <td>
                                                                    <VField label="Nama :">
                                                                        <VControl>
                                                                            <VInput type="text" class="input"
                                                                                v-model="input.namaPerawatSirkulerText" />
                                                                        </VControl>
                                                                    </VField>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <VField label="Tanda Tangan :">
                                                                        <TandaTangan v-model="input.TTDperawatAnestesi"
                                                                            :elemenID="'TTDperawatAnestesi'"
                                                                            :width="'150'" :height="'150'"
                                                                            class="dek" />
                                                                    </VField>
                                                                </td>
                                                                <td>
                                                                    <VField label="Tanda Tangan :">
                                                                        <TandaTangan v-model="input.TTDperawatInstrumen"
                                                                            :elemenID="'TTDperawatInstrumen'"
                                                                            :width="'150'" :height="'150'"
                                                                            class="dek" />
                                                                    </VField>
                                                                </td>
                                                                <td>
                                                                    <VField label="Tanda Tangan :">
                                                                        <TandaTangan v-model="input.TTDperawatSirkuler"
                                                                            :elemenID="'TTDperawatSirkuler'"
                                                                            :width="'150'" :height="'150'"
                                                                            class="dek" />
                                                                    </VField>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <VField label="Jam :">
                                                                        <VDatePicker v-model="input.jamPerawatAnestesi"
                                                                            mode="time" is24hr>
                                                                            <template
                                                                                #default="{ inputValue, inputEvents }">
                                                                                <VControl icon="feather:clock"
                                                                                    fullwidth>
                                                                                    <VInput :value="inputValue"
                                                                                        v-on="inputEvents" />
                                                                                </VControl>
                                                                            </template>
                                                                        </VDatePicker>
                                                                    </VField>
                                                                </td>
                                                                <td>
                                                                    <VField label="Jam :">
                                                                        <VDatePicker v-model="input.jamPerawatInstrumen"
                                                                            mode="time" is24hr>
                                                                            <template
                                                                                #default="{ inputValue, inputEvents }">
                                                                                <VControl icon="feather:clock"
                                                                                    fullwidth>
                                                                                    <VInput :value="inputValue"
                                                                                        v-on="inputEvents" />
                                                                                </VControl>
                                                                            </template>
                                                                        </VDatePicker>
                                                                    </VField>
                                                                </td>
                                                                <td>
                                                                    <VField label="Jam :">
                                                                        <VDatePicker v-model="input.jamPerawatSirkuler"
                                                                            mode="time" is24hr>
                                                                            <template
                                                                                #default="{ inputValue, inputEvents }">
                                                                                <VControl icon="feather:clock"
                                                                                    fullwidth>
                                                                                    <VInput :value="inputValue"
                                                                                        v-on="inputEvents" />
                                                                                </VControl>
                                                                            </template>
                                                                        </VDatePicker>
                                                                    </VField>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </Fieldset>
                        </div>
                    </Fieldset>
                </div>
                <div class="column is-12 is-multiline">
                    <h1 style="font-weight: bold;">PENGHITUNGAN INTRA OPERATIF</h1>
                    <div class="columns">
                        <!-- Table Section -->
                        <div class="column is-12 pl-0" style="overflow: auto;">
                            <table class="table is-bordered">
                                <thead>
                                    <tr>
                                        <td style="text-align: center; min-width: 100px;">#</td>
                                        <td style="text-align: center; min-width: 140px;">Jenis/Nama Item Yang Dihitung
                                        </td>
                                        <td style="text-align: center; min-width: 100px;">Penghitungan Awal</td>
                                        <td colspan="4" style="text-align: center; min-width: 400px;">Penambahan Item
                                        </td>
                                        <td style="text-align: center; min-width: 100px;">Total Tambahan</td>
                                        <td style="text-align: center; min-width: 100px;">Penghitungan Pertama</td>
                                        <td colspan="4" style="text-align: center; min-width: 400px;">Penambahan Kedua
                                        </td>
                                        <td style="text-align: center; min-width: 100px;">Total Tambahan</td>
                                        <td style="text-align: center; min-width: 100px;">Penghitungan Akhir</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in input.details" :key="index">
                                        <td style="vertical-align: inherit">
                                            <div class="column">
                                                <VButtons style="justify-content:space-around">
                                                    <VIconButton type="button" raised circle icon="feather:plus"
                                                        @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                                    </VIconButton>
                                                    <VIconButton class="mt-1" v-if="index > 0" type="button" raised
                                                        circle icon="feather:trash" @click="removeItem(index)"
                                                        color="danger">
                                                    </VIconButton>
                                                </VButtons>
                                            </div>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOJenisNamaItem"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOPenghitunganAwal"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOPenambahanItem1"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOPenambahanItem2"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOPenambahanItem3"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOPenambahanItem4"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOTotalTambahan"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOPenghitunganPertama"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOPenambahanKedua1"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOPenambahanKedua2"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOPenambahanKedua3"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOPenambahanKedua4"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOTotalTambahandua"></VTextarea>
                                            </VField>
                                        </td>
                                        <td>
                                            <VField>
                                                <VTextarea rows="2" v-model="item.iOPenghitunganAkhir"></VTextarea>
                                            </VField>
                                        </td>
                                    </tr>
                                </tbody>
                                <tr>
                                    <td colspan="14">
                                        Instrumen Set / Single
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in input.details2" :key="index">
                                    <td style="vertical-align: inherit">
                                        <div class="column">
                                            <VButtons style="justify-content:space-around">
                                                <VIconButton type="button" raised circle icon="feather:plus"
                                                    @click="addNewItem2()" color="info" v-tooltip.bubble="'Tambah '">
                                                </VIconButton>
                                                <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                                    icon="feather:trash" @click="removeItem2(index)" color="danger">
                                                </VIconButton>
                                            </VButtons>
                                        </div>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOJenisNamaItem2"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOPenghitunganAwal2"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOPenambahanItem5"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOPenambahanItem6"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOPenambahanItem7"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOPenambahanItem8"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOTotalTambahan2"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOPenghitunganPertam2"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOPenambahanKedua5"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOPenambahanKedua6"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOPenambahanKedua7"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOPenambahanKedua8"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOTotalTambahandua2"></VTextarea>
                                        </VField>
                                    </td>
                                    <td>
                                        <VField>
                                            <VTextarea rows="2" v-model="item.iOPenghitunganAkhir2"></VTextarea>
                                        </VField>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="column is-12">
                        <table class="table is-bordered is-fullwidth">
                            <tr>
                                <td></td>
                                <td>
                                    Penghitungan Awal
                                </td>
                                <td>
                                    Penghitungan Pertama
                                </td>
                                <td>
                                    Penghitungan Akhir
                                </td>
                                <td>
                                    Keterangan
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Nama dan tanda tangan Perawat instrumen
                                </td>
                                <td>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.pIOPerawatInstrumen1" :suggestions="d_Petugas"
                                                @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" class="mt-2" />
                                        </VControl>
                                    </VField>
                                    <VField>
                                        <TandaTangan v-model="input.TTDpIOPerawatInstrumen1"
                                            :elemenID="'TTDpIOPerawatInstrumen1'" :width="'150'" :height="'150'"
                                            class="dek" />
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.pIOPerawatInstrumen2" :suggestions="d_Petugas"
                                                @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" class="mt-2" />
                                        </VControl>
                                    </VField>
                                    <VField>
                                        <TandaTangan v-model="input.TTDpIOPerawatInstrumen1"
                                            :elemenID="'TTDpIOPerawatInstrumen2'" :width="'150'" :height="'150'"
                                            class="dek" />
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.pIOPerawatInstrumen3" :suggestions="d_Petugas"
                                                @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" class="mt-2" />
                                        </VControl>
                                    </VField>
                                    <VField>
                                        <TandaTangan v-model="input.TTDpIOPerawatInstrumen3"
                                            :elemenID="'TTDpIOPerawatInstrumen3'" :width="'150'" :height="'150'"
                                            class="dek" />
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.pIOPerawatInstrumen4" :suggestions="d_Petugas"
                                                @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" class="mt-2" />
                                        </VControl>
                                    </VField>
                                    <VField>
                                        <TandaTangan v-model="input.TTDpIOPerawatInstrumen4"
                                            :elemenID="'TTDpIOPerawatInstrumen4'" :width="'150'" :height="'150'"
                                            class="dek" />
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Nama dan tanda tangan Perawat sirkuler
                                </td>
                                <td>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.pIOPerawatSirkuler1" :suggestions="d_Petugas"
                                                @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" class="mt-2" />
                                        </VControl>
                                    </VField>
                                    <VField>
                                        <TandaTangan v-model="input.TTDpIOPerawatSirkuler1"
                                            :elemenID="'TTDpIOPerawatSirkuler1'" :width="'150'" :height="'150'"
                                            class="dek" />
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.pIOPerawatSirkuler2" :suggestions="d_Petugas"
                                                @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" class="mt-2" />
                                        </VControl>
                                    </VField>
                                    <VField>
                                        <TandaTangan v-model="input.TTDpIOPerawatSirkuler2"
                                            :elemenID="'TTDpIOPerawatSirkuler2'" :width="'150'" :height="'150'"
                                            class="dek" />
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.pIOPerawatSirkuler3" :suggestions="d_Petugas"
                                                @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" class="mt-2" />
                                        </VControl>
                                    </VField>
                                    <VField>
                                        <TandaTangan v-model="input.TTDpIOPerawatSirkuler3"
                                            :elemenID="'TTDpIOPerawatSirkuler3'" :width="'150'" :height="'150'"
                                            class="dek" />
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.pIOPerawatSirkuler4" :suggestions="d_Petugas"
                                                @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" class="mt-2" />
                                        </VControl>
                                    </VField>
                                    <VField>
                                        <TandaTangan v-model="input.TTDpIOPerawatSirkuler4"
                                            :elemenID="'TTDpIOPerawatSirkuler4'" :width="'150'" :height="'150'"
                                            class="dek" />
                                    </VField>
                                </td>
                            </tr>
                            <tr>
                                <td>Benar penghitungan</td>
                                <td colspan="4">
                                    <div class="flex-container" style="display: flex; flex-wrap: wrap;">
                                        <div class="flex-item" style="flex: 1 1 33%; padding: 10px;">
                                            <VField style="display: flex; justify-content: space-evenly;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.pIOBenarPenghitunganYa" />
                                                </VControl>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak, Jika tidak,"
                                                        v-model="input.pIOBenarPenghitunganTidak" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="flex-item" style="flex: 1 1 33%; padding: 10px;">
                                            <VField>
                                                <span>sepengetahuan dokter</span>
                                            </VField>
                                            <VField>
                                                <span>Dilakukan X-ray</span>
                                            </VField>
                                        </div>
                                        <div class="flex-item" style="flex: 1 1 33%; padding: 10px;">
                                            <VField style="display: flex; justify-content: space-evenly;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.pIOBenarPenghitunganYa2" />
                                                </VControl>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.pIOBenarPenghitunganTidak2" />
                                                </VControl>
                                            </VField>
                                            <VField style="display: flex; justify-content: space-evenly;">
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Ya"
                                                        label="Ya" v-model="input.pIOBenarPenghitunganYa3" />
                                                </VControl>
                                                <VControl raw subcontrol>
                                                    <VCheckbox class="p-0" color="primary" square true-value="Tidak"
                                                        label="Tidak" v-model="input.pIOBenarPenghitunganTidak3" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="column is-12 mt-3-min">
                    <Fieldset :toggleable="true" legend="POST OPERATIF">
                        <div class="columns is-multiline p-3">
                            <div class="column is-12">
                                <Fieldset :toggleable="true" legend="PENGKAJIAN (DATA FOKUS)">
                                    <h1 style="font-weight: bold;">DATA SUBYEKTIF :</h1>
                                    <div class="column is-12" v-for="(datas) in pasienMengeluh">
                                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                        <div class="columns is-multiline">
                                            <div class="column is-2" v-for="(data) in datas.value">
                                                <VField v-if="data.type == 'checkboxBebas'"
                                                    style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                    <VControl raw subcontrol
                                                        style="display: flex; align-items: center;">
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle" :label="data.subTitle"
                                                            v-model="input[data.model]" />
                                                        <VInput type="text" class="input"
                                                            v-model="input[data.model2]" />
                                                    </VControl>
                                                </VField>
                                                <VField v-else-if="data.type == 'checkbox'">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="p-0" color="primary" square
                                                            :true-value="data.subTitle" :label="data.subTitle"
                                                            v-model="input[data.model]" />
                                                    </VControl>
                                                </VField>
                                                <VField v-else-if="data.type == 'checkbox2'">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField>
                                                        <VControl raw subcontrol
                                                            style="display: flex; align-items: center;">
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle2" :label="data.subTitle2"
                                                                v-model="input[data.model2]" />
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model3]" />
                                                        </VControl>
                                                    </VField>
                                                </VField>
                                                <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                    style="margin-bottom: 0.5rem;">
                                                    <VControl raw subcontrol>
                                                        <input v-model="input[data.model]" class="input p-0" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12" v-for="(datas) in pOVitalSign">
                                        <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                        <div class="columns is-multiline">
                                            <div class="column is-4" v-for="(data) in datas.value">
                                                <VField addons :label="data.subTitle"
                                                    v-if="data.type == 'textBoxChoice'">
                                                    <VControl>
                                                        <VInput type="text" class="input" v-model="input[data.model]" />
                                                    </VControl>
                                                    <VControl class="field-addon-body">
                                                        <VButton static>{{ data.nama }}</VButton>
                                                    </VControl>
                                                </VField>
                                                <VField :label="data.subTitle" v-else-if="data.type == 'textBox'"
                                                    style="margin-bottom: 0.5rem;">
                                                    <VControl raw subcontrol>
                                                        <input v-model="input[data.model]" class="input p-0" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="columns is-multiline">
                                        <div class="column is-4" v-for="(datas) in pOBreath">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-6" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'checkboxBebas'"
                                                        style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                        <VControl raw subcontrol
                                                            style="display: flex; align-items: center;">
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model2]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'DBcheckboxBebas'"
                                                        style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                        <VControl raw subcontrol style="width:50%">
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                        </VControl>
                                                        <VControl raw subcontrol
                                                            style="display: flex; align-items: center; width:50%">
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle2" :label="data.subTitle2"
                                                                v-model="input[data.model2]" />
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model3]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'checkboxBebas'"
                                                        style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                        <VControl raw subcontrol
                                                            style="display: flex; align-items: center;">
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model2]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'checkbox'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'DBcheckbox'"
                                                        style="margin-bottom: 0.5rem; display: flex">
                                                        <VControl raw subcontrol style="width:50%">
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                        </VControl>
                                                        <VControl raw subcontrol style="width:50%">
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle2" :label="data.subTitle2"
                                                                v-model="input[data.model2]" />
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else-if="data.type == 'checkboxText'"
                                                        style="margin-bottom: 0.5rem; display: flex;">
                                                        <VControl raw subcontrol style="width: 50%;">
                                                            <VCheckbox class="p-0" color="primary" square
                                                                :true-value="data.subTitle" :label="data.subTitle"
                                                                v-model="input[data.model]" />
                                                        </VControl>
                                                        <VControl raw subcontrol style="width: 50%;">
                                                            <VInput type="text" class="input"
                                                                v-model="input[data.model2]" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-4" v-for="(datas) in pOKeterangan">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                                            <div class="columns is-multiline">
                                                <div class="column is-12" v-for="(data) in datas.value">
                                                    <VField v-if="data.type == 'textarea'"
                                                        style="margin-bottom: 0.5rem;">
                                                        <VTextarea class="textarea" rows="2"
                                                            :placeholder="data.subTitle" v-model="input[data.model]">
                                                        </VTextarea>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <h1 style="font-weight: bold; margin-bottom: 1rem;">KETERANGAN LAIN</h1>
                                            <VField>
                                                <VTextarea rows="2" placeholder="Keterangan Lain" class="textarea"
                                                    v-model="input.pOKeteranganLainText"></VTextarea>
                                            </VField>
                                        </div>
                                    </div>

                                    <table class="table is-fullwidth is-bordered">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center; width: 50%;">Diagnosa Keperawatan</th>
                                                <th style="text-align: center; width: 50%;">Rencana Keperawatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(datas, index) in diagnosaKeperawatan3"
                                                :key="'diagnosa-' + index">
                                                <td>
                                                    <div v-for="(data) in datas.value" :key="data.id">
                                                        <VField v-if="data.type == 'checkboxBebas'"
                                                            style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                            <VControl raw subcontrol
                                                                style="display: flex; align-items: center;">
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                                <VTextarea rows="4" v-model="input[data.model2]">
                                                                </VTextarea>
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'checkbox'"
                                                            style="margin-bottom: 0.5rem;">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'textBox'"
                                                            style="display: flex;">
                                                            <span>- &nbsp;&nbsp;</span>
                                                            <VField>
                                                                <VTextarea rows="2" v-model="input[data.model]">
                                                                </VTextarea>
                                                            </VField>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'text'" style="display: flex;">
                                                            <span>- {{ data.subTitle }}</span>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'textarea'">
                                                            <VField :label="data.subTitle">
                                                                <VTextarea class="textarea" rows="2"
                                                                    v-model="input[data.model]">
                                                                </VTextarea>
                                                            </VField>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td v-if="rencanaKeperawatan3[index]">
                                                    <div v-for="(data) in rencanaKeperawatan3[index].value"
                                                        :key="data.id">
                                                        <VField v-if="data.type == 'checkboxBebas'"
                                                            style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                            <VControl raw subcontrol
                                                                style="display: flex; align-items: center;">
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                                <VInput type="text" class="input"
                                                                    v-model="input[data.model2]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'checkbox'"
                                                            style="margin-bottom: 0.5rem;">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    :true-value="data.subTitle" :label="data.subTitle"
                                                                    v-model="input[data.model]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'textBox'"
                                                            style="display: flex;">
                                                            <span>-</span>
                                                            <VControl>
                                                                <VInput type="text" class="input"
                                                                    v-model="input[data.model]" />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'text'" style="display: flex;">
                                                            <span>- {{ data.subTitle }}</span>
                                                        </VField>
                                                        <VField v-else-if="data.type == 'textarea'"
                                                            style="margin-bottom: 0.5rem;">
                                                            <VField :label="data.subTitle">
                                                                <VTextarea class="textarea" rows="2"
                                                                    v-model="input[data.model]">
                                                                </VTextarea>
                                                            </VField>
                                                        </VField>
                                                    </div>
                                                </td>
                                                <td v-else></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="column is-12">
                                        <table class="table is-fullwidth is-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center; width: 50%;">Tindakan Keperawatan
                                                    </th>
                                                    <th style="text-align: center; width: 50%;">Evaluasi Keperawatan
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(datas, index) in tindakanKeperawatan3"
                                                    :key="'diagnosa-' + index">
                                                    <td>
                                                        <div v-for="(data) in datas.value" :key="data.id">
                                                            <VField v-if="data.type == 'checkboxBebas'"
                                                                style="margin-bottom: 0.5rem; display:flex; align-items: center;">
                                                                <VControl raw subcontrol
                                                                    style="display: flex; align-items: center;">
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                    <VTextarea rows="4" v-model="input[data.model2]">
                                                                    </VTextarea>
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'checkbox'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textBox'"
                                                                style="display: flex;">
                                                                <span>- &nbsp;&nbsp;</span>
                                                                <VField>
                                                                    <VTextarea rows="2" v-model="input[data.model]">
                                                                    </VTextarea>
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textBox2'"
                                                                style="display: flex;">
                                                                <span>- &nbsp;&nbsp;</span>
                                                                <VField>
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input[data.model]" />
                                                                    </VControl>
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'text'"
                                                                style="display: flex;">
                                                                <span>- {{ data.subTitle }}</span>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual1'"
                                                                style="display: flex;">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td>
                                                                            <VField style="margin-bottom: 0.5rem;">
                                                                                <VControl raw subcontrol>
                                                                                    <VCheckbox class="p-0"
                                                                                        color="primary" square
                                                                                        true-value="KolaborasiPencucianLuka"
                                                                                        label="Kolaborasi Pencucian Luka"
                                                                                        v-model="input.tK2KolaborasiPencucianLuka" />
                                                                                </VControl>
                                                                            </VField>
                                                                        </td>
                                                                        <td>
                                                                            <VField style="margin-bottom: 0.5rem;">
                                                                                <span>Jenis cairan yang digunakan</span>
                                                                            </VField>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <VField style="margin-bottom: 0.5rem;">
                                                                            </VField>
                                                                        </td>
                                                                        <td>
                                                                            <VField>
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.tK2JenisCairanText1" />
                                                                                </VControl>
                                                                            </VField>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <VField style="margin-bottom: 0.5rem;">
                                                                            </VField>
                                                                        </td>
                                                                        <td>
                                                                            <VField>
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.tK2JenisCairanText2" />
                                                                                </VControl>
                                                                            </VField>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual2'">
                                                                <VField label="Ukuran drain no :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eK2UkuranDrainNoText" />
                                                                    </VControl>
                                                                </VField>
                                                                <VField label="Lokasi drain :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eK2LokasiDrainText" />
                                                                    </VControl>
                                                                </VField>
                                                            </VField>
                                                            <VField :label="data.subTitle"
                                                                v-else-if="data.type == 'combo'">
                                                                <VControl class="prime-auto">
                                                                    <AutoComplete v-model="input[data.model]"
                                                                        :suggestions="d_Petugas"
                                                                        @complete="fetchPetugas($event)"
                                                                        :optionLabel="'label'" :dropdown="true"
                                                                        :minLength="3" :appendTo="'body'"
                                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                        class="mt-2" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textarea'">
                                                                <VField :label="data.subTitle">
                                                                    <VTextarea rows="2" v-model="input[data.model]">
                                                                    </VTextarea>
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'text2'"
                                                                style="display: flex;">
                                                                <span style="text-align: center;">{{ data.subTitle
                                                                    }}</span>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td v-if="evaluasiKeperawatan3[index]">
                                                        <div v-for="(data) in evaluasiKeperawatan3[index].value"
                                                            :key="data.id">
                                                            <VField v-if="data.type == 'checkboxBebas'"
                                                                style="margin-bottom: 0.5rem; display:flex;">
                                                                <VControl raw subcontrol style="display: flex; ">
                                                                    <VCheckbox class="p-0 mt-3" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                </VControl>
                                                                <VInput style="width: 80%" type="text"
                                                                    class="ml-3 input" v-model="input[data.model2]" />
                                                            </VField>
                                                            <VField v-else-if="data.type == 'checkbox'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'checkbox2'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square :true-value="data.subTitle"
                                                                                    :label="data.subTitle"
                                                                                    v-model="input[data.model]" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square :true-value="data.subTitle2"
                                                                                    :label="data.subTitle2"
                                                                                    v-model="input[data.model2]" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'checkbox3'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td>
                                                                            <VField>
                                                                                <VControl raw subcontrol>
                                                                                    <VCheckbox class="p-0"
                                                                                        color="primary" square
                                                                                        :true-value="data.subTitle"
                                                                                        :label="data.subTitle"
                                                                                        v-model="input[data.model]" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField>
                                                                                <VControl raw subcontrol
                                                                                    style="display: flex;">
                                                                                    <VCheckbox class="p-0 mt-2"
                                                                                        color="primary" square
                                                                                        :true-value="data.subTitle3"
                                                                                        :label="data.subTitle3"
                                                                                        v-model="input[data.model3]" />
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input[data.model4]" />
                                                                                </VControl>
                                                                            </VField>
                                                                        </td>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square :true-value="data.subTitle2"
                                                                                    :label="data.subTitle2"
                                                                                    v-model="input[data.model2]" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'checkboxText'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                    <VField>
                                                                        <VTextarea rows="2"
                                                                            v-model="input[data.model2]">
                                                                        </VTextarea>
                                                                    </VField>
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'checkboxText2'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <VControl raw subcontrol style="display: flex;">
                                                                    <VCheckbox class="p-0 mt-4" color="primary" square
                                                                        :true-value="data.subTitle"
                                                                        :label="data.subTitle"
                                                                        v-model="input[data.model]" />
                                                                    <VField>
                                                                        <VControl>
                                                                            <VInput type="text" class="input mx-1"
                                                                                style="width: 90%"
                                                                                v-model="input[data.model2]" />
                                                                        </VControl>
                                                                    </VField>
                                                                    <VField class="mt-4">
                                                                        <span>,</span>
                                                                    </VField>
                                                                    <VField addons>
                                                                        <VControl>
                                                                            <VInput type="text" class="input mx-4"
                                                                                v-model="input[data.model3]" />
                                                                        </VControl>
                                                                        <VControl class="field-addon-body">
                                                                            <VButton static>{{ data.nama }}</VButton>
                                                                        </VControl>
                                                                    </VField>
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'ttd'">
                                                                <VField>
                                                                    <span>{{ data.subTitle }}</span>
                                                                </VField>
                                                                <VField>
                                                                    <TandaTangan v-model="data.model"
                                                                        :elemenID="data.model" :width="'150'"
                                                                        :height="'150'" class="dek" />
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textBox'"
                                                                style="display: flex;">
                                                                <span>-</span>
                                                                <VControl>
                                                                    <VInput type="text" class="input"
                                                                        v-model="input[data.model]" />
                                                                </VControl>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'text'"
                                                                style="display: flex;">
                                                                <span>- {{ data.subTitle }}</span>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'text2'"
                                                                style="display: flex;">
                                                                <span style="text-align: center;">{{ data.subTitle
                                                                    }}</span>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual1'">
                                                                <VField>
                                                                    <span>Preparation solution yang digunakan :</span>
                                                                </VField>
                                                                <VField style="display: flex;">
                                                                    <VControl raw subcontrol style="width: 50%;">
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="PovidoneIodine"
                                                                            label="Povidone iodine"
                                                                            v-model="input.eK2PovidoneIodine" />
                                                                    </VControl>
                                                                    <VControl raw subcontrol style="width: 50%;">
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="Alkohol" label="Alkohol"
                                                                            v-model="input.eK2Alkohol" />
                                                                    </VControl>
                                                                </VField>
                                                                <VField style="display: flex;">
                                                                    <VControl raw subcontrol style="width: 50%;">
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="ChlorhexidineAlcohol"
                                                                            label="Chlorhexidine alcohol"
                                                                            v-model="input.eK2ChlorhexidineAlcohol" />
                                                                    </VControl>
                                                                    <VControl raw subcontrol style="width: 50%;">
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="Lainnya" label="Lainnya"
                                                                            v-model="input.eK2Lainnya" />
                                                                    </VControl>
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual2'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square
                                                                                    true-value="DiatermiBerfungsiBaik"
                                                                                    label="Diatermi Berfungsi Baik"
                                                                                    v-model="input.eK2DiatermiBerfungsiBaik" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square true-value="TidakDiperlukan"
                                                                                    label="Tidak Diperlukan"
                                                                                    v-model="input.eK2TidakDiperlukan1" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual3'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square
                                                                                    true-value="TerpasangDanTermonitor"
                                                                                    label="Terpasang Dan Termonitor"
                                                                                    v-model="input.eK2TerpasangDanTermonitor" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square true-value="TidakDiperlukan"
                                                                                    label="Tidak Diperlukan"
                                                                                    v-model="input.eK2TidakDiperlukan2" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual4'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td>
                                                                            <span style="text-align: center;">Nama
                                                                                obat</span>
                                                                        </td>
                                                                        <td>
                                                                            <span
                                                                                style="text-align: center;">Lokasi</span>
                                                                        </td>
                                                                        <td>
                                                                            <span style="text-align: center;">Total
                                                                                dosis</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2NamaObat1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2Lokasi1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2TotalDosis1" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2NamaObat2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2Lokasi2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2TotalDosis2" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual5'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <span style="text-align: center;">Kondisi
                                                                                hangat</span>
                                                                        </td>
                                                                        <td>
                                                                            <span style="text-align: center;">Total
                                                                                volume</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square :true-value="Ya" label="Ya"
                                                                                    v-model="input.tK2KondisiHangatYa1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square :true-value="Tidak"
                                                                                    label="Tidak"
                                                                                    v-model="input.tK2KondisiHangatTidak1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VField addons>
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.tK2KondisiHangatText1" />
                                                                                </VControl>
                                                                                <VControl class="field-addon-body">
                                                                                    <VButton static>ml</VButton>
                                                                                </VControl>
                                                                            </VField>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square :true-value="Ya" label="Ya"
                                                                                    v-model="input.tK2KondisiHangatYa2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square :true-value="Tidak"
                                                                                    label="Tidak"
                                                                                    v-model="input.tK2KondisiHangatTidak2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td>
                                                                            <VField addons>
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.tK2KondisiHangatText2" />
                                                                                </VControl>
                                                                                <VControl class="field-addon-body">
                                                                                    <VButton static>ml</VButton>
                                                                                </VControl>
                                                                            </VField>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual6'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td style="width: 30%;">
                                                                            <span style="text-align: center;">Lokasi
                                                                                Luka</span>
                                                                        </td>
                                                                        <td style="width: 70%;">
                                                                            <span style="text-align: center;">Tipe
                                                                                dressing</span>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual7'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td style="width: 30%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas1" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square true-value="TulleGrass"
                                                                                    label="Tulle grass"
                                                                                    v-model="input.eK2TulleGrass1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas2" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText2" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas3" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText3" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0" color="primary"
                                                                                    square true-value="TulleGrass"
                                                                                    label="Tulle grass"
                                                                                    v-model="input.eK2TulleGrass2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas4" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText4" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 30%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas5" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText5" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas6" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText6" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            <VControl raw subcontrol
                                                                                style="display: flex;">
                                                                                <VCheckbox class="p-0 mt-3"
                                                                                    color="primary" square
                                                                                    true-value="LokasiLukaBebas"
                                                                                    label=""
                                                                                    v-model="input.eK2LokasiLukaBebas7" />
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2LokasiLukaBebasText7" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual8'">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="Utuh" label="Utuh"
                                                                            v-model="input.eK2Utuh" />
                                                                    </VControl>
                                                                </VField>
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="p-0" color="primary" square
                                                                            true-value="Tidak" label="Tidak, jelaskan"
                                                                            v-model="input.eK2TidakJelaskan" />
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eK2TidakJelaskanText" />
                                                                    </VControl>
                                                                </VField>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual9'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <th style="width: 10%;">NO</th>
                                                                        <th style="width: 40%;">NAMA BAHAN</th>
                                                                        <th style="width: 50%;">TIPE FIKSASI</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 10%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eKNomorBahan1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2NamaBahan1" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 50%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2TipeFiksasi1" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 10%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eKNomorBahan2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2NamaBahan2" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 50%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2TipeFiksasi2" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 10%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eKNomorBahan3" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2NamaBahan3" />
                                                                            </VControl>
                                                                        </td>
                                                                        <td style="width: 50%;">
                                                                            <VControl>
                                                                                <VInput type="text" class="input"
                                                                                    v-model="input.eK2TipeFiksasi3" />
                                                                            </VControl>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'manual10'">
                                                                <table class="table is-fullwidth is-bordered">
                                                                    <tr>
                                                                        <td style="width: 50%;">
                                                                            <VField label="KU :">
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.eK2KUText" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField label="TD :">
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.eK2TDText" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField label="Nadi :">
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.eK2NadiText" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField label="Jumlah cairan infus :">
                                                                                <VField addons>
                                                                                    <VControl>
                                                                                        <VInput type="text"
                                                                                            class="input"
                                                                                            v-model="input.eK2JumlahCairanInfusText" />
                                                                                    </VControl>
                                                                                    <VControl class="field-addon-body">
                                                                                        <VButton static>cc</VButton>
                                                                                    </VControl>
                                                                                </VField>
                                                                            </VField>
                                                                            <VField label="Jumlah transfusi :">
                                                                                <VField addons>
                                                                                    <VControl>
                                                                                        <VInput type="text"
                                                                                            class="input"
                                                                                            v-model="input.eK2JumlahTransfusiText" />
                                                                                    </VControl>
                                                                                    <VControl class="field-addon-body">
                                                                                        <VButton static>cc</VButton>
                                                                                    </VControl>
                                                                                </VField>
                                                                            </VField>
                                                                        </td>
                                                                        <td style="width: 50%;">
                                                                            <VField label="Respirasi :">
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.eK2RespirasiText" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField label="Suhu :">
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.eK2SuhuText" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField label="Saturasi :">
                                                                                <VControl>
                                                                                    <VInput type="text" class="input"
                                                                                        v-model="input.eK2SaturasiText" />
                                                                                </VControl>
                                                                            </VField>
                                                                            <VField label="Jumlah perdarahan :">
                                                                                <VField addons>
                                                                                    <VControl>
                                                                                        <VInput type="text"
                                                                                            class="input"
                                                                                            v-model="input.eK2JumlahPerdarahanText" />
                                                                                    </VControl>
                                                                                    <VControl class="field-addon-body">
                                                                                        <VButton static>cc</VButton>
                                                                                    </VControl>
                                                                                </VField>
                                                                            </VField>
                                                                            <VField label="Jumlah urine :">
                                                                                <VField addons>
                                                                                    <VControl>
                                                                                        <VInput type="text"
                                                                                            class="input"
                                                                                            v-model="input.eK2JumlahUrineText" />
                                                                                    </VControl>
                                                                                    <VControl class="field-addon-body">
                                                                                        <VButton static>cc</VButton>
                                                                                    </VControl>
                                                                                </VField>
                                                                            </VField>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </VField>
                                                            <VField v-else-if="data.type == 'textarea'"
                                                                style="margin-bottom: 0.5rem;">
                                                                <VField :label="data.subTitle">
                                                                    <VTextarea rows="2" v-model="input[data.model]">
                                                                    </VTextarea>
                                                                </VField>
                                                            </VField>
                                                        </div>
                                                    </td>
                                                    <td v-else></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="column is-12">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox class="p-0" color="primary" square
                                                                        true-value="EvaluasiKondisiPasienSebelumPindahKeRuangPerawatanPulangKeRumah"
                                                                        label="Evaluasi kondisi pasien sebelum pindah ke ruang perawatan/pulang ke rumah"
                                                                        v-model="input.evaluasiKondisiPasien" />
                                                                </VControl>
                                                            </VField>
                                                        </div>

                                                        <div class="columns is-multiline">
                                                            <div class="column is-3">
                                                                <VField label="Kesadaran :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eVPKesadaran" />
                                                                    </VControl>
                                                                </VField>
                                                                <VField label="TD :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eVPTD" />
                                                                    </VControl>
                                                                </VField>
                                                                <VField label="RR :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eVPRR" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-3">
                                                                <VField label="Nadi :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eVPNadi" />
                                                                    </VControl>
                                                                </VField>
                                                                <VField label="Suhu :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eVPSuhu" />
                                                                    </VControl>
                                                                </VField>
                                                                <VField label="Keluhan lain :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eVPKeluhanLain" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-3">
                                                                <VField label="Saturasi :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eVPSaturasi" />
                                                                    </VControl>
                                                                </VField>
                                                                <VField label="Bromage score :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eVPBromageScore" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                            <div class="column is-3">
                                                                <VField label="Skala nyeri :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eVPSkalaNyeri" />
                                                                    </VControl>
                                                                </VField>
                                                                <VField label="Aldrete score :">
                                                                    <VControl>
                                                                        <VInput type="text" class="input"
                                                                            v-model="input.eVPAldreteScore" />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" color="primary" square
                                                                    true-value="HandoverDenganPetugasRuangan"
                                                                    label="Handover dengan petugas ruangan"
                                                                    v-model="input.tK3HandoverDenganPetugasRuangan" />
                                                            </VControl>
                                                        </VField>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <VField label="KETERANGAN LAIN">
                                                            <VTextarea rows="2" class="textarea"
                                                                v-model="input.keteranganLainText">
                                                            </VTextarea>
                                                        </VField>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span style="text-align: center;">Nama Perawat :</span>
                                                        <VField>
                                                            <VControl class="prime-auto">
                                                                <AutoComplete v-model="input.namaPerawatPostOperatif"
                                                                    :suggestions="d_Petugas"
                                                                    @complete="fetchPetugas($event)"
                                                                    :optionLabel="'label'" :dropdown="true"
                                                                    :minLength="3" :appendTo="'body'"
                                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                    class="mt-2" />
                                                            </VControl>
                                                        </VField>
                                                    </td>
                                                    <td>
                                                        <VField label="Tanda Tangan :">
                                                            <TandaTangan v-model="input.TTDperawatPostOperatif"
                                                                :elemenID="'TTDperawatPostOperatif'" :width="'150'"
                                                                :height="'150'" class="dek" />
                                                        </VField>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </Fieldset>
                            </div>
                            <div class="column is-12">
                            </div>
                            <div class="column is-12">
                            </div>
                        </div>
                    </Fieldset>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core';
import { useApi } from '/@src/composable/useApi';
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue';
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router';
import { useHead } from '@vueuse/head';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import { useThemeColors } from '/@src/composable/useThemeColors';
import { useUserSession } from '/@src/stores/userSession';
import * as H from '/@src/utils/appHelper';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue';
import ImgDraw from '../page-emr-plugins/img-draw.vue';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import * as EMR from '../page-emr-plugins/asuhan-keperawatan-peri-operatif';
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
useHead({ title: 'Asuhan Keperawatan Peri-Operatif' + import.meta.env.VITE_PROJECT })
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let Pengkajian = ref(EMR.Pengkajian())
let TimOperasi = ref(EMR.TimOperasi())
let DataFokus = ref(EMR.DataFokus())
let DataObyektif = ref(EMR.DataObyektif())
let pOVitalSign = ref(EMR.pOVitalSign())
let diagnosaKeperawatan = ref(EMR.diagnosaKeperawatan())
let diagnosaKeperawatan2 = ref(EMR.diagnosaKeperawatan2())
let diagnosaKeperawatan3 = ref(EMR.diagnosaKeperawatan3())
let rencanaKeperawatan = ref(EMR.rencanaKeperawatan())
let rencanaKeperawatan2 = ref(EMR.rencanaKeperawatan2())
let rencanaKeperawatan3 = ref(EMR.rencanaKeperawatan3())
let tindakanKeperawatan = ref(EMR.tindakanKeperawatan())
let evaluasiKeperawatan = ref(EMR.evaluasiKeperawatan())
let tindakanKeperawatan2 = ref(EMR.tindakanKeperawatan2())
let evaluasiKeperawatan2 = ref(EMR.evaluasiKeperawatan2())
let tindakanKeperawatan3 = ref(EMR.tindakanKeperawatan3())
let evaluasiKeperawatan3 = ref(EMR.evaluasiKeperawatan3())
let kondisiPasien = ref(EMR.kondisiPasien())
let pasienMengeluh = ref(EMR.pasienMengeluh())
let setInstrumen = ref(EMR.setInstrumen())
let alatLain = ref(EMR.alatLain())
let jenisAnestesi = ref(EMR.jenisAnestesi())
let iOBreath = ref(EMR.iOBreath())
let pOBreath = ref(EMR.pOBreath())
let pOKeterangan = ref(EMR.pOKeterangan())


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
        COLLECTION: 'AsuhanKeperawatanPeriOperatif',
    }
)
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const dataTTD: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const d_Petugas: any = ref([])
const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
}
const d_Dokter: any = ref([])
const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`
    ).then((response) => {
        d_Dokter.value = response
    })
}
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const MARKINGSITE: any = ref('')
const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}
const addNewItem = () => {
    const predefinedValues = ["Kasa kecil", "Kasa besar", "Kasa reyteg", "Deepers", "Needles atraumatic", "Needles ordinary", "Syringe needle", "Arteri klem"];
    const currentIndex = input.value.details.length;

    const newItem = {
        no: currentIndex + 1,
        iOJenisNamaItem: currentIndex < predefinedValues.length ? predefinedValues[currentIndex] : "", // Cycle through predefined values
        iOPenghitunganAwal: "",
        iOPenambahanItem1: "",
        iOPenambahanItem2: "",
        iOTotalTambahan: "",
        iOPenghitunganAkhir: "",
    };

    input.value.details.push(newItem);
};

const removeItem2 = (index: any) => {
    input.value.details2.splice(index, 1)
}
const addNewItem2 = () => {
    let newItem: any = {}
    newItem = { no: input.value.details2[input.value.details2.length - 1].no + 1 }
    input.value.details2.push(newItem);
}

const input: any = ref({
    tanggal: new Date(),
    waktuOperasi1_: new Date(),
    waktuOperasi2_: new Date(),
    waktuOperasi3_: new Date(),
    waktuOperasi4_: new Date(),
    waktuOperasi5_: new Date(),
    waktuOperasi6_: new Date(),
    waktuOperasi7_: new Date(),
    waktuOperasi8_: new Date(),
    waktuOperasi9_: new Date(),
    jamPackingTenggorokan_: new Date(),
    torniquetJamMulai: new Date(),
    torniquetJamSelesai: new Date(),
    jamPerawatAnestesi: new Date(),
    jamPerawatInstrumen: new Date(),
    jamPerawatSirkuler: new Date(),
    eKJam_: new Date(),
    const predefinedValues = ["Kasa kecil", "Kasa besar", "Kasa reyteg", "Deepers", "Needles atraumatic", "Needles ordinary", "Syringe needle", "Arteri klem"];

    details: [
        {
            no: 1,
            iOJenisNamaItem: "Kasa kecil",
        },
        {
            no: 2,
            iOJenisNamaItem: "Kasa besar",
        },
        {
            no: 3,
            iOJenisNamaItem: "Kasa reyteg",
        },
        {
            no: 4,
            iOJenisNamaItem: "Deepers",
        },
        {
            no: 5,
            iOJenisNamaItem: "Needles atraumatic",
        },
        {
            no: 6,
            iOJenisNamaItem: "Needles ordinary",
        },
        {
            no: 7,
            iOJenisNamaItem: "Syringe needle",
        },
        {
            no: 8,
            iOJenisNamaItem: "Arteri klem",
        }
    ],
    details2: [{
        no: 1,
    }],
})

const activeValue: any = ref(0)
const idTemplate: any = ref('');
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

const ttdIds = ["signature_1", "rK2TTDPerawat_", "TTDperawatAnestesi", "TTDperawatInstrumen", "TTDperawatSirkuler", "TTDpIOPerawatInstrumen1", "TTDpIOPerawatInstrumen2", "TTDpIOPerawatInstrumen3", "TTDpIOPerawatInstrumen4", "TTDpIOPerawatSirkuler1", "TTDpIOPerawatSirkuler2", "TTDpIOPerawatSirkuler3", "TTDpIOPerawatSirkuler4", "TTDperawatPostOperatif", "Gambar1"];

const loadRiwayat = async () => {
    // if (NOREC_EMRPASIEN.value == '') return
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        setTandaTanganOrGambar(response);
    }
}

// Function to set Tanda Tangan or Gambar to dataTTD.value
const setTandaTanganOrGambar = (response) => {
    ttdIds.forEach((id) => {
        // Check if the response contains the id
        if (response[0][id]) {
            // If the id contains 'gambar' (case insensitive), use loadGambar
            if (id.toLowerCase().includes("gambar")) {
                loadGambar(id, response[0][id]); // Call loadGambar for 'gambar' ids
                dataTTD.value[id] = response[0][id]; // Set the gambar value into dataTTD.value
            } else {
                // Otherwise, set directly to dataTTD and use tandaTangan().set
                dataTTD.value[id] = response[0][id]; // Set the value into dataTTD
                H.tandaTangan().set(id, response[0][id]); // Set it using tandaTangan()
            }

        }
    });
};

const loadGambar = async (element_id: string, value: string) => {
    let sigCanvas: any = document.getElementById(element_id);
    if (sigCanvas) {
        let context = sigCanvas.getContext("2d");
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        let imagess = value
        let background = new Image();
        background.src = imagess
        background.onload = function () {
            context.drawImage(background, 0, 0, 250, 250);
        }
    }
}

const changeAll = () => {
    let total = 0;
    rencanaKeperawatan.value.forEach((data, index) => {
        if (input.value[index]['rKLaksanakanProtap_All']) {
            console.log('CHECKBOX', input.value[index]['rKLaksanakanProtap_All'])
        }
    });
};

const clearCanvas = (canvas: any) => {

    var sigCanvas: any = document.getElementById(canvas);
    var context = sigCanvas.getContext("2d");
    context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);

}

const addSignaturesToObject = (object: any, ids: string[], tandaTangan: any) => {
    ids.forEach((id) => {
        const key = id; // Sanitize ID to create a valid key
        object[key] = tandaTangan().get(id);
    });
};

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
    addSignaturesToObject(object, ttdIds, H.tandaTangan);
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
            input.value.id = response.id
        }).catch((e: any) => {
            isLoading.value = false
        })
}
const simpanTemplate = () => {
    if (input.value.namatemplate == null) {
        H.alert('warning', 'Isi nama template terlebih dahulu!')
        return;
    }
    let ID = idTemplate.value ? idTemplate.value : ''
    let object: any = {}

    object = input.value
    object.nocm = props.pasien.nocm
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

    useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
        isLoading.value = false
        checkTemplate.value = false
        input.value.namatemplate = null
        delete object.namatemplate;
        delete object['_id'];
        delete object.nocm;
        delete object.pasien;
        delete object.regisstrasi;
        object.id = '';
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const checkTemplate: any = ref(false)
const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
        isLoading.value = false
        if (responselast.length) {
            for (var x = 0; x < responselast.length; x++) {
                responselast[x].no = x + 1
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
    showModalTemplateFix.value = false
}

const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}
const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=id&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
}
const setTandaTangan = async (e: any) => {
    const response = await useApi().get(
        `/emr/tanda-tangan/${e.value.value}`)
    if (response != null) {
        H.tandaTangan().set("signature_1", response.ttd)
        input.value.tandaTanganPerawat = response.ttd
    } else {
        H.tandaTangan().set("signature_1", '')
    }
}

watch(
  () => input.['rKLaksanakanProtap_All'].value,
  (newValue, oldValue) => {
    console.log('CHECKBOX', newValue)
  }
)

const kembaliKeun = () => {
    window.history.back()
}
const setAutoFill = async () => {
    input.value.dokterRawat = props.registrasi.dokter
}
setView()
setAutoFill()

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
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
</script>

<style lang="scss"></style>
