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
                            @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline" v-if="item.classgrid">
                    <div class="column" :class="item.classgrid">
                        <div v-for="(data, index) in listDynamic.kolom1" :key="data.id">
                            <div v-if="data.type == 'label'">
                                <div class="column is-12">
                                    <label :style="data.style"> {{ data.caption }}</label>
                                </div>
                            </div>
                            <div v-if="data.type == 'checkbox'">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.caption"
                                                        :label="data.caption" color="primary" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="data.type == 'radio'">
                                <div class="column is-12" v-for="itemss in data.captionradio" :key="itemss">
                                    <VRadio v-model="input[data.model]" :value="itemss" :label="itemss" name="{{itemss}}"
                                        square color="primary" />
                                </div>
                            </div>
                            <div v-if="data.type == 'textbox'">
                                <div class="column is-12">
                                    <VField :label="data.caption"></VField>
                                    <VField addons v-if="data.satuan != null && data.satuan != ''">
                                        <VControl expanded>
                                            <VInput type="text" class="input" :placeholder="data.caption"
                                                v-model="input[data.model]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ data.satuan }}</VButton>
                                        </VControl>
                                    </VField>
                                    <VField v-else>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" class="input" :placeholder="data.caption"
                                                v-model="input[data.model]" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'checkboxtextbox'">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-10">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.caption"
                                                        :label="data.caption" color="primary" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-8" style="margin-left:10px;margin-top:-20px">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" :placeholder="data.caption"
                                                        v-model="input[data.model]" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="data.type == 'datetime'">
                                <div class="column is-12">
                                    <VField :label="data.caption"></VField>
                                    <VField addons>
                                        <VDatePicker v-model="input[data.model]" mode="dateTime" style="width: 100%"
                                            trim-weeks :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" :placeholder="data.caption"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'time'">
                                <VField :label="data.caption"></VField>
                                <VField addons>
                                    <VDatePicker v-model="input[data.model]" color="green" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl>
                                                    <VInput class="input form-timepicker" :value="inputValue"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div v-if="data.type == 'textarea'">
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>{{ data.caption }}</VLabel>
                                        <VControl>
                                            <VTextarea v-model="input[data.model]" rows="2" :placeholder="data.caption">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'combobox'">
                                <div class="column is-12">
                                    <VField :label="data.caption" class="is-rounded-select_Z  is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="fa:search" fullwidth class="prime-auto ">
                                            <AutoComplete v-model="input[data.model]" :suggestions="d_Combo[data.id]"
                                                @complete="fetchCombo($event, data.id, data.cbotable)"
                                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="ketik untuk mencari..." />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'image'">
                                <div class="column is-12">
                                    <img :src="data.caption" :style="data.style" alt="Image">
                                </div>
                            </div>
                            <div class="column is-12" style="margin-left: 20px;margin-top: -15px"
                                v-if="data.child.length > 0">
                                <div v-for="(data2, index2) in data.child" :key="data2.id">
                                    <div v-if="data2.type == 'label'">
                                        <div class="column is-12">
                                            <VField>
                                                <VLabel :style="data2.style"> {{ data2.caption }}</VLabel>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'checkbox'">
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data2.model]"
                                                                :true-value="data2.caption" :label="data2.caption"
                                                                color="primary" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'radio'">
                                        <div class="column is-12" v-for="itemss in data2.captionradio" :key="itemss">
                                            <VRadio v-model="input[data2.model]" :value="itemss" :label="itemss"
                                                name="{{itemss}}" square color="primary" />
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'textbox'">
                                        <div class="column is-12">
                                            <VField :label="data2.caption"></VField>
                                            <VField addons v-if="data2.satuan != null && data2.satuan != ''">
                                                <VControl expanded>
                                                    <VInput type="text" class="input" :placeholder="data2.caption"
                                                        v-model="input[data2.model]" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>{{ data2.satuan }}</VButton>
                                                </VControl>
                                            </VField>
                                            <VField v-else>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" class="input" :placeholder="data2.caption"
                                                        v-model="input[data2.model]" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>

                                    <div v-if="data2.type == 'checkboxtextbox'">
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column is-10">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data2.model]"
                                                                :true-value="data2.caption" :label="data2.caption"
                                                                color="primary" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-8" style="margin-left:10px;margin-top:-20px">
                                                    <VField>
                                                        <VControl>
                                                            <VInput type="text" class="input" :placeholder="data2.caption"
                                                                v-model="input[data2.model]" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'datetime'">
                                        <div class="column is-12">
                                            <VField :label="data2.caption"></VField>
                                            <VField addons>
                                                <VDatePicker v-model="input[data2.model]" mode="dateTime"
                                                    style="width: 100%" trim-weeks :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" :placeholder="data2.caption"
                                                                    v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'time'">
                                        <VField :label="data2.caption"></VField>
                                        <VField addons>
                                            <VDatePicker v-model="input[data2.model]" color="green" mode="time" is24hr>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField>
                                                        <VControl>
                                                            <VInput class="input form-timepicker" :value="inputValue"
                                                                v-on="inputEvents" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                    </div>
                                    <div v-if="data2.type == 'textarea'">
                                        <div class="column is-12">
                                            <VField>
                                                <VLabel>{{ data2.caption }}</VLabel>
                                                <VControl>
                                                    <VTextarea v-model="input[data2.model]" rows="2"
                                                        :placeholder="data2.caption">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'combobox'">
                                        <div class="column is-12">
                                            <VField :label="data2.caption"
                                                class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="fa:search" fullwidth class="prime-auto ">
                                                    <AutoComplete v-model="input[data2.model]"
                                                        :suggestions="d_Combo[data2.id]"
                                                        @complete="fetchCombo($event, data2.id, data2.cbotable)"
                                                        :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik untuk mencari..." />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'image'">
                                        <div class="column is-12">
                                            <img :src="data2.caption" :style="data2.style" alt="Image">
                                        </div>
                                    </div>
                                    <div class="column is-12" style="margin-left: 40px;margin-top: -25px"
                                        v-if="data2.child.length > 0">
                                        <div v-for="(data3, index2) in data2.child" :key="data3.id">
                                            <div v-if="data3.type == 'label'">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabel :style="data3.style"> {{ data3.caption }}</VLabel>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'checkbox'">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data3.model]"
                                                                        :true-value="data" :label="data3.caption"
                                                                        color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'radio'">
                                                <div class="column is-12" v-for="itemss in data3.captionradio"
                                                    :key="itemss">
                                                    <VRadio v-model="input[data3.model]" :value="itemss" :label="itemss"
                                                        name="{{itemss}}" square color="primary" />
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'textbox'">
                                                <div class="column is-12">
                                                    <VField :label="data3.caption"></VField>
                                                    <VField addons v-if="data3.satuan != null && data3.satuan != ''">
                                                        <VControl expanded>
                                                            <VInput type="text" class="input" :placeholder="data3.caption"
                                                                v-model="input[data3.model]" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>{{ data3.satuan }}</VButton>
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else>
                                                        <VControl icon="feather:bookmark">
                                                            <VInput type="text" class="input" :placeholder="data3.caption"
                                                                v-model="input[data3.model]" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'checkboxtextbox'">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-10">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data3.model]"
                                                                        :true-value="data3.caption" :label="data3.caption"
                                                                        color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-8" style="margin-left:10px;margin-top:-20px">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput type="text" class="input"
                                                                        :placeholder="data3.caption"
                                                                        v-model="input[data3.model]" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'datetime'">
                                                <div class="column is-12">
                                                    <VField :label="data3.caption"></VField>
                                                    <VField addons>
                                                        <VDatePicker v-model="input[data3.model]" mode="dateTime"
                                                            style="width: 100%" trim-weeks :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue"
                                                                            :placeholder="data3.caption"
                                                                            v-on="inputEvents" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'time'">
                                                <VField :label="data3.caption"></VField>
                                                <VField addons>
                                                    <VDatePicker v-model="input[data3.model]" color="green" mode="time"
                                                        is24hr>
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput class="input form-timepicker"
                                                                        :value="inputValue" v-on="inputEvents" />
                                                                </VControl>
                                                            </VField>
                                                        </template>
                                                    </VDatePicker>
                                                </VField>
                                            </div>
                                            <div v-if="data3.type == 'textarea'">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabel>{{ data3.caption }}</VLabel>
                                                        <VControl>
                                                            <VTextarea v-model="input[data3.model]" rows="2"
                                                                :placeholder="data3.caption">
                                                            </VTextarea>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'combobox'">
                                                <div class="column is-12">
                                                    <VField :label="data3.caption"
                                                        class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="fa:search" fullwidth class="prime-auto ">
                                                            <AutoComplete v-model="input[data3.model]"
                                                                :suggestions="d_Combo[data3.id]"
                                                                @complete="fetchCombo($event, data3.id, data3.cbotable)"
                                                                :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                :field="'label'" placeholder="ketik untuk mencari..." />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'image'">
                                                <div class="column is-12">
                                                    <img :src="data3.caption" :style="data3.style" alt="Image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column" :class="item.classgrid">
                        <div v-for="(data, index) in listDynamic.kolom2" :key="data.id">
                            <div v-if="data.type == 'label'">
                                <div class="column is-12">
                                    <label :style="data.style"> {{ data.caption }}</label>
                                </div>
                            </div>
                            <div v-if="data.type == 'checkbox'">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.caption"
                                                        :label="data.caption" color="primary" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="data.type == 'radio'">
                                <div class="column is-12" v-for="itemss in data.captionradio" :key="itemss">
                                    <VRadio v-model="input[data.model]" :value="itemss" :label="itemss" name="{{itemss}}"
                                        square color="primary" />
                                </div>
                            </div>
                            <div v-if="data.type == 'textbox'">
                                <div class="column is-12">
                                    <VField :label="data.caption"></VField>
                                    <VField addons v-if="data.satuan != null && data.satuan != ''">
                                        <VControl expanded>
                                            <VInput type="text" class="input" :placeholder="data.caption"
                                                v-model="input[data.model]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ data.satuan }}</VButton>
                                        </VControl>
                                    </VField>
                                    <VField v-else>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" class="input" :placeholder="data.caption"
                                                v-model="input[data.model]" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'checkboxtextbox'">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-10">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.caption"
                                                        :label="data.caption" color="primary" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-8" style="margin-left:10px;margin-top:-20px">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" :placeholder="data.caption"
                                                        v-model="input[data.model]" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="data.type == 'datetime'">
                                <div class="column is-12">
                                    <VField :label="data.caption"></VField>
                                    <VField addons>
                                        <VDatePicker v-model="input[data.model]" mode="dateTime" style="width: 100%"
                                            trim-weeks :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" :placeholder="data.caption"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'time'">
                                <VField :label="data.caption"></VField>
                                <VField addons>
                                    <VDatePicker v-model="input[data.model]" color="green" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl>
                                                    <VInput class="input form-timepicker" :value="inputValue"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div v-if="data.type == 'textarea'">
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>{{ data.caption }}</VLabel>
                                        <VControl>
                                            <VTextarea v-model="input[data.model]" rows="2" :placeholder="data.caption">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'combobox'">
                                <div class="column is-12">
                                    <VField :label="data.caption" class="is-rounded-select_Z  is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="fa:search" fullwidth class="prime-auto ">
                                            <AutoComplete v-model="input[data.model]" :suggestions="d_Combo[data.id]"
                                                @complete="fetchCombo($event, data.id, data.cbotable)"
                                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="ketik untuk mencari..." />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'image'">
                                <div class="column is-12">
                                    <img :src="data.caption" :style="data.style" alt="Image">
                                </div>
                            </div>
                            <div class="column is-12" style="margin-left: 20px;margin-top: -15px"
                                v-if="data.child.length > 0">
                                <div v-for="(data2, index2) in data.child" :key="data2.id">
                                    <div v-if="data2.type == 'label'">
                                        <div class="column is-12">
                                            <VField>
                                                <VLabel :style="data2.style"> {{ data2.caption }}</VLabel>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'checkbox'">
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data2.model]"
                                                                :true-value="data2.caption" :label="data2.caption"
                                                                color="primary" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'radio'">
                                        <div class="column is-12" v-for="itemss in data2.captionradio" :key="itemss">
                                            <VRadio v-model="input[data2.model]" :value="itemss" :label="itemss"
                                                name="{{itemss}}" square color="primary" />
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'textbox'">
                                        <div class="column is-12">
                                            <VField :label="data2.caption"></VField>
                                            <VField addons v-if="data2.satuan != null && data2.satuan != ''">
                                                <VControl expanded>
                                                    <VInput type="text" class="input" :placeholder="data2.caption"
                                                        v-model="input[data2.model]" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>{{ data2.satuan }}</VButton>
                                                </VControl>
                                            </VField>
                                            <VField v-else>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" class="input" :placeholder="data2.caption"
                                                        v-model="input[data2.model]" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>

                                    <div v-if="data2.type == 'checkboxtextbox'">
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column is-10">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data2.model]"
                                                                :true-value="data2.caption" :label="data2.caption"
                                                                color="primary" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-8" style="margin-left:10px;margin-top:-20px">
                                                    <VField>
                                                        <VControl>
                                                            <VInput type="text" class="input" :placeholder="data2.caption"
                                                                v-model="input[data2.model]" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'datetime'">
                                        <div class="column is-12">
                                            <VField :label="data2.caption"></VField>
                                            <VField addons>
                                                <VDatePicker v-model="input[data2.model]" mode="dateTime"
                                                    style="width: 100%" trim-weeks :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" :placeholder="data2.caption"
                                                                    v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'time'">
                                        <VField :label="data2.caption"></VField>
                                        <VField addons>
                                            <VDatePicker v-model="input[data2.model]" color="green" mode="time" is24hr>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField>
                                                        <VControl>
                                                            <VInput class="input form-timepicker" :value="inputValue"
                                                                v-on="inputEvents" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                    </div>
                                    <div v-if="data2.type == 'textarea'">
                                        <div class="column is-12">
                                            <VField>
                                                <VLabel>{{ data2.caption }}</VLabel>
                                                <VControl>
                                                    <VTextarea v-model="input[data2.model]" rows="2"
                                                        :placeholder="data2.caption">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'combobox'">
                                        <div class="column is-12">
                                            <VField :label="data2.caption"
                                                class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="fa:search" fullwidth class="prime-auto ">
                                                    <AutoComplete v-model="input[data2.model]"
                                                        :suggestions="d_Combo[data2.id]"
                                                        @complete="fetchCombo($event, data2.id, data2.cbotable)"
                                                        :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik untuk mencari..." />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'image'">
                                        <div class="column is-12">
                                            <img :src="data2.caption" :style="data2.style" alt="Image">
                                        </div>
                                    </div>
                                    <div class="column is-12" style="margin-left: 40px;margin-top: -25px"
                                        v-if="data2.child.length > 0">
                                        <div v-for="(data3, index2) in data2.child" :key="data3.id">
                                            <div v-if="data3.type == 'label'">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabel :style="data3.style"> {{ data3.caption }}</VLabel>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'checkbox'">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data3.model]"
                                                                        :true-value="data" :label="data3.caption"
                                                                        color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'radio'">
                                                <div class="column is-12" v-for="itemss in data3.captionradio"
                                                    :key="itemss">
                                                    <VRadio v-model="input[data3.model]" :value="itemss" :label="itemss"
                                                        name="{{itemss}}" square color="primary" />
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'textbox'">
                                                <div class="column is-12">
                                                    <VField :label="data3.caption"></VField>
                                                    <VField addons v-if="data3.satuan != null && data3.satuan != ''">
                                                        <VControl expanded>
                                                            <VInput type="text" class="input" :placeholder="data3.caption"
                                                                v-model="input[data3.model]" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>{{ data3.satuan }}</VButton>
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else>
                                                        <VControl icon="feather:bookmark">
                                                            <VInput type="text" class="input" :placeholder="data3.caption"
                                                                v-model="input[data3.model]" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'checkboxtextbox'">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-10">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data3.model]"
                                                                        :true-value="data3.caption" :label="data3.caption"
                                                                        color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-8" style="margin-left:10px;margin-top:-20px">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput type="text" class="input"
                                                                        :placeholder="data3.caption"
                                                                        v-model="input[data3.model]" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'datetime'">
                                                <div class="column is-12">
                                                    <VField :label="data3.caption"></VField>
                                                    <VField addons>
                                                        <VDatePicker v-model="input[data3.model]" mode="dateTime"
                                                            style="width: 100%" trim-weeks :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue"
                                                                            :placeholder="data3.caption"
                                                                            v-on="inputEvents" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'time'">
                                                <VField :label="data3.caption"></VField>
                                                <VField addons>
                                                    <VDatePicker v-model="input[data3.model]" color="green" mode="time"
                                                        is24hr>
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput class="input form-timepicker"
                                                                        :value="inputValue" v-on="inputEvents" />
                                                                </VControl>
                                                            </VField>
                                                        </template>
                                                    </VDatePicker>
                                                </VField>
                                            </div>
                                            <div v-if="data3.type == 'textarea'">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabel>{{ data3.caption }}</VLabel>
                                                        <VControl>
                                                            <VTextarea v-model="input[data3.model]" rows="2"
                                                                :placeholder="data3.caption">
                                                            </VTextarea>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'combobox'">
                                                <div class="column is-12">
                                                    <VField :label="data3.caption"
                                                        class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="fa:search" fullwidth class="prime-auto ">
                                                            <AutoComplete v-model="input[data3.model]"
                                                                :suggestions="d_Combo[data3.id]"
                                                                @complete="fetchCombo($event, data3.id, data3.cbotable)"
                                                                :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                :field="'label'" placeholder="ketik untuk mencari..." />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'image'">
                                                <div class="column is-12">
                                                    <img :src="data3.caption" :style="data3.style" alt="Image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column" :class="item.classgrid">
                        <div v-for="(data, index) in listDynamic.kolom3" :key="data.id">
                            <div v-if="data.type == 'label'">
                                <div class="column is-12">
                                    <label :style="data.style"> {{ data.caption }}</label>
                                </div>
                            </div>
                            <div v-if="data.type == 'checkbox'">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.caption"
                                                        :label="data.caption" color="primary" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="data.type == 'radio'">
                                <div class="column is-12" v-for="itemss in data.captionradio" :key="itemss">
                                    <VRadio v-model="input[data.model]" :value="itemss" :label="itemss" name="{{itemss}}"
                                        square color="primary" />
                                </div>
                            </div>
                            <div v-if="data.type == 'textbox'">
                                <div class="column is-12">
                                    <VField :label="data.caption"></VField>
                                    <VField addons v-if="data.satuan != null && data.satuan != ''">
                                        <VControl expanded>
                                            <VInput type="text" class="input" :placeholder="data.caption"
                                                v-model="input[data.model]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ data.satuan }}</VButton>
                                        </VControl>
                                    </VField>
                                    <VField v-else>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" class="input" :placeholder="data.caption"
                                                v-model="input[data.model]" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'checkboxtextbox'">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-10">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.caption"
                                                        :label="data.caption" color="primary" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-8" style="margin-left:10px;margin-top:-20px">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" :placeholder="data.caption"
                                                        v-model="input[data.model]" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="data.type == 'datetime'">
                                <div class="column is-12">
                                    <VField :label="data.caption"></VField>
                                    <VField addons>
                                        <VDatePicker v-model="input[data.model]" mode="dateTime" style="width: 100%"
                                            trim-weeks :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" :placeholder="data.caption"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'time'">
                                <VField :label="data.caption"></VField>
                                <VField addons>
                                    <VDatePicker v-model="input[data.model]" color="green" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl>
                                                    <VInput class="input form-timepicker" :value="inputValue"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div v-if="data.type == 'textarea'">
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>{{ data.caption }}</VLabel>
                                        <VControl>
                                            <VTextarea v-model="input[data.model]" rows="2" :placeholder="data.caption">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'combobox'">
                                <div class="column is-12">
                                    <VField :label="data.caption" class="is-rounded-select_Z  is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="fa:search" fullwidth class="prime-auto ">
                                            <AutoComplete v-model="input[data.model]" :suggestions="d_Combo[data.id]"
                                                @complete="fetchCombo($event, data.id, data.cbotable)"
                                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="ketik untuk mencari..." />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'image'">
                                <div class="column is-12">
                                    <img :src="data.caption" :style="data.style" alt="Image">
                                </div>
                            </div>
                            <div class="column is-12" style="margin-left: 20px;margin-top: -15px"
                                v-if="data.child.length > 0">
                                <div v-for="(data2, index2) in data.child" :key="data2.id">
                                    <div v-if="data2.type == 'label'">
                                        <div class="column is-12">
                                            <VField>
                                                <VLabel :style="data2.style"> {{ data2.caption }}</VLabel>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'checkbox'">
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data2.model]"
                                                                :true-value="data2.caption" :label="data2.caption"
                                                                color="primary" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'radio'">
                                        <div class="column is-12" v-for="itemss in data2.captionradio" :key="itemss">
                                            <VRadio v-model="input[data2.model]" :value="itemss" :label="itemss"
                                                name="{{itemss}}" square color="primary" />
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'textbox'">
                                        <div class="column is-12">
                                            <VField :label="data2.caption"></VField>
                                            <VField addons v-if="data2.satuan != null && data2.satuan != ''">
                                                <VControl expanded>
                                                    <VInput type="text" class="input" :placeholder="data2.caption"
                                                        v-model="input[data2.model]" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>{{ data2.satuan }}</VButton>
                                                </VControl>
                                            </VField>
                                            <VField v-else>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" class="input" :placeholder="data2.caption"
                                                        v-model="input[data2.model]" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>

                                    <div v-if="data2.type == 'checkboxtextbox'">
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column is-10">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data2.model]"
                                                                :true-value="data2.caption" :label="data2.caption"
                                                                color="primary" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-8" style="margin-left:10px;margin-top:-20px">
                                                    <VField>
                                                        <VControl>
                                                            <VInput type="text" class="input" :placeholder="data2.caption"
                                                                v-model="input[data2.model]" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'datetime'">
                                        <div class="column is-12">
                                            <VField :label="data2.caption"></VField>
                                            <VField addons>
                                                <VDatePicker v-model="input[data2.model]" mode="dateTime"
                                                    style="width: 100%" trim-weeks :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" :placeholder="data2.caption"
                                                                    v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'time'">
                                        <VField :label="data2.caption"></VField>
                                        <VField addons>
                                            <VDatePicker v-model="input[data2.model]" color="green" mode="time" is24hr>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField>
                                                        <VControl>
                                                            <VInput class="input form-timepicker" :value="inputValue"
                                                                v-on="inputEvents" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                    </div>
                                    <div v-if="data2.type == 'textarea'">
                                        <div class="column is-12">
                                            <VField>
                                                <VLabel>{{ data2.caption }}</VLabel>
                                                <VControl>
                                                    <VTextarea v-model="input[data2.model]" rows="2"
                                                        :placeholder="data2.caption">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'combobox'">
                                        <div class="column is-12">
                                            <VField :label="data2.caption"
                                                class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="fa:search" fullwidth class="prime-auto ">
                                                    <AutoComplete v-model="input[data2.model]"
                                                        :suggestions="d_Combo[data2.id]"
                                                        @complete="fetchCombo($event, data2.id, data2.cbotable)"
                                                        :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik untuk mencari..." />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'image'">
                                        <div class="column is-12">
                                            <img :src="data2.caption" :style="data2.style" alt="Image">
                                        </div>
                                    </div>
                                    <div class="column is-12" style="margin-left: 40px;margin-top: -25px"
                                        v-if="data2.child.length > 0">
                                        <div v-for="(data3, index2) in data2.child" :key="data3.id">
                                            <div v-if="data3.type == 'label'">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabel :style="data3.style"> {{ data3.caption }}</VLabel>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'checkbox'">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data3.model]"
                                                                        :true-value="data" :label="data3.caption"
                                                                        color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'radio'">
                                                <div class="column is-12" v-for="itemss in data3.captionradio"
                                                    :key="itemss">
                                                    <VRadio v-model="input[data3.model]" :value="itemss" :label="itemss"
                                                        name="{{itemss}}" square color="primary" />
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'textbox'">
                                                <div class="column is-12">
                                                    <VField :label="data3.caption"></VField>
                                                    <VField addons v-if="data3.satuan != null && data3.satuan != ''">
                                                        <VControl expanded>
                                                            <VInput type="text" class="input" :placeholder="data3.caption"
                                                                v-model="input[data3.model]" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>{{ data3.satuan }}</VButton>
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else>
                                                        <VControl icon="feather:bookmark">
                                                            <VInput type="text" class="input" :placeholder="data3.caption"
                                                                v-model="input[data3.model]" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'checkboxtextbox'">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-10">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data3.model]"
                                                                        :true-value="data3.caption" :label="data3.caption"
                                                                        color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-8" style="margin-left:10px;margin-top:-20px">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput type="text" class="input"
                                                                        :placeholder="data3.caption"
                                                                        v-model="input[data3.model]" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'datetime'">
                                                <div class="column is-12">
                                                    <VField :label="data3.caption"></VField>
                                                    <VField addons>
                                                        <VDatePicker v-model="input[data3.model]" mode="dateTime"
                                                            style="width: 100%" trim-weeks :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue"
                                                                            :placeholder="data3.caption"
                                                                            v-on="inputEvents" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'time'">
                                                <VField :label="data3.caption"></VField>
                                                <VField addons>
                                                    <VDatePicker v-model="input[data3.model]" color="green" mode="time"
                                                        is24hr>
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput class="input form-timepicker"
                                                                        :value="inputValue" v-on="inputEvents" />
                                                                </VControl>
                                                            </VField>
                                                        </template>
                                                    </VDatePicker>
                                                </VField>
                                            </div>
                                            <div v-if="data3.type == 'textarea'">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabel>{{ data3.caption }}</VLabel>
                                                        <VControl>
                                                            <VTextarea v-model="input[data3.model]" rows="2"
                                                                :placeholder="data3.caption">
                                                            </VTextarea>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'combobox'">
                                                <div class="column is-12">
                                                    <VField :label="data3.caption"
                                                        class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="fa:search" fullwidth class="prime-auto ">
                                                            <AutoComplete v-model="input[data3.model]"
                                                                :suggestions="d_Combo[data3.id]"
                                                                @complete="fetchCombo($event, data3.id, data3.cbotable)"
                                                                :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                :field="'label'" placeholder="ketik untuk mencari..." />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'image'">
                                                <div class="column is-12">
                                                    <img :src="data3.caption" :style="data3.style" alt="Image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column" :class="item.classgrid">
                        <div v-for="(data, index) in listDynamic.kolom4" :key="data.id">
                            <div v-if="data.type == 'label'">
                                <div class="column is-12">
                                    <label :style="data.style"> {{ data.caption }}</label>
                                </div>
                            </div>
                            <div v-if="data.type == 'checkbox'">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.caption"
                                                        :label="data.caption" color="primary" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="data.type == 'radio'">
                                <div class="column is-12" v-for="itemss in data.captionradio" :key="itemss">
                                    <VRadio v-model="input[data.model]" :value="itemss" :label="itemss" name="{{itemss}}"
                                        square color="primary" />
                                </div>
                            </div>
                            <div v-if="data.type == 'textbox'">
                                <div class="column is-12">
                                    <VField :label="data.caption"></VField>
                                    <VField addons v-if="data.satuan != null && data.satuan != ''">
                                        <VControl expanded>
                                            <VInput type="text" class="input" :placeholder="data.caption"
                                                v-model="input[data.model]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ data.satuan }}</VButton>
                                        </VControl>
                                    </VField>
                                    <VField v-else>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" class="input" :placeholder="data.caption"
                                                v-model="input[data.model]" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'checkboxtextbox'">
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-10">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.caption"
                                                        :label="data.caption" color="primary" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-8" style="margin-left:10px;margin-top:-20px">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input" :placeholder="data.caption"
                                                        v-model="input[data.model]" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="data.type == 'datetime'">
                                <div class="column is-12">
                                    <VField :label="data.caption"></VField>
                                    <VField addons>
                                        <VDatePicker v-model="input[data.model]" mode="dateTime" style="width: 100%"
                                            trim-weeks :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" :placeholder="data.caption"
                                                            v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'time'">
                                <VField :label="data.caption"></VField>
                                <VField addons>
                                    <VDatePicker v-model="input[data.model]" color="green" mode="time" is24hr>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl>
                                                    <VInput class="input form-timepicker" :value="inputValue"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </div>
                            <div v-if="data.type == 'textarea'">
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>{{ data.caption }}</VLabel>
                                        <VControl>
                                            <VTextarea v-model="input[data.model]" rows="2" :placeholder="data.caption">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'combobox'">
                                <div class="column is-12">
                                    <VField :label="data.caption" class="is-rounded-select_Z  is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="fa:search" fullwidth class="prime-auto ">
                                            <AutoComplete v-model="input[data.model]" :suggestions="d_Combo[data.id]"
                                                @complete="fetchCombo($event, data.id, data.cbotable)"
                                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                placeholder="ketik untuk mencari..." />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div v-if="data.type == 'image'">
                                <div class="column is-12">
                                    <img :src="data.caption" :style="data.style" alt="Image">
                                </div>
                            </div>
                            <div class="column is-12" style="margin-left: 20px;margin-top: -15px"
                                v-if="data.child.length > 0">
                                <div v-for="(data2, index2) in data.child" :key="data2.id">
                                    <div v-if="data2.type == 'label'">
                                        <div class="column is-12">
                                            <VField>
                                                <VLabel :style="data2.style"> {{ data2.caption }}</VLabel>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'checkbox'">
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data2.model]"
                                                                :true-value="data2.caption" :label="data2.caption"
                                                                color="primary" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'radio'">
                                        <div class="column is-12" v-for="itemss in data2.captionradio" :key="itemss">
                                            <VRadio v-model="input[data2.model]" :value="itemss" :label="itemss"
                                                name="{{itemss}}" square color="primary" />
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'textbox'">
                                        <div class="column is-12">
                                            <VField :label="data2.caption"></VField>
                                            <VField addons v-if="data2.satuan != null && data2.satuan != ''">
                                                <VControl expanded>
                                                    <VInput type="text" class="input" :placeholder="data2.caption"
                                                        v-model="input[data2.model]" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>{{ data2.satuan }}</VButton>
                                                </VControl>
                                            </VField>
                                            <VField v-else>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" class="input" :placeholder="data2.caption"
                                                        v-model="input[data2.model]" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>

                                    <div v-if="data2.type == 'checkboxtextbox'">
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column is-10">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data2.model]"
                                                                :true-value="data2.caption" :label="data2.caption"
                                                                color="primary" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-8" style="margin-left:10px;margin-top:-20px">
                                                    <VField>
                                                        <VControl>
                                                            <VInput type="text" class="input" :placeholder="data2.caption"
                                                                v-model="input[data2.model]" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'datetime'">
                                        <div class="column is-12">
                                            <VField :label="data2.caption"></VField>
                                            <VField addons>
                                                <VDatePicker v-model="input[data2.model]" mode="dateTime"
                                                    style="width: 100%" trim-weeks :max-date="new Date()">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VControl icon="feather:calendar" fullwidth>
                                                                <VInput :value="inputValue" :placeholder="data2.caption"
                                                                    v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'time'">
                                        <VField :label="data2.caption"></VField>
                                        <VField addons>
                                            <VDatePicker v-model="input[data2.model]" color="green" mode="time" is24hr>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField>
                                                        <VControl>
                                                            <VInput class="input form-timepicker" :value="inputValue"
                                                                v-on="inputEvents" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </VField>
                                    </div>
                                    <div v-if="data2.type == 'textarea'">
                                        <div class="column is-12">
                                            <VField>
                                                <VLabel>{{ data2.caption }}</VLabel>
                                                <VControl>
                                                    <VTextarea v-model="input[data2.model]" rows="2"
                                                        :placeholder="data2.caption">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'combobox'">
                                        <div class="column is-12">
                                            <VField :label="data2.caption"
                                                class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="fa:search" fullwidth class="prime-auto ">
                                                    <AutoComplete v-model="input[data2.model]"
                                                        :suggestions="d_Combo[data2.id]"
                                                        @complete="fetchCombo($event, data2.id, data2.cbotable)"
                                                        :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik untuk mencari..." />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <div v-if="data2.type == 'image'">
                                        <div class="column is-12">
                                            <img :src="data2.caption" :style="data2.style" alt="Image">
                                        </div>
                                    </div>
                                    <div class="column is-12" style="margin-left: 40px;margin-top: -25px"
                                        v-if="data2.child.length > 0">
                                        <div v-for="(data3, index2) in data2.child" :key="data3.id">
                                            <div v-if="data3.type == 'label'">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabel :style="data3.style"> {{ data3.caption }}</VLabel>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'checkbox'">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data3.model]"
                                                                        :true-value="data" :label="data3.caption"
                                                                        color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'radio'">
                                                <div class="column is-12" v-for="itemss in data3.captionradio"
                                                    :key="itemss">
                                                    <VRadio v-model="input[data3.model]" :value="itemss" :label="itemss"
                                                        name="{{itemss}}" square color="primary" />
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'textbox'">
                                                <div class="column is-12">
                                                    <VField :label="data3.caption"></VField>
                                                    <VField addons v-if="data3.satuan != null && data3.satuan != ''">
                                                        <VControl expanded>
                                                            <VInput type="text" class="input" :placeholder="data3.caption"
                                                                v-model="input[data3.model]" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>{{ data3.satuan }}</VButton>
                                                        </VControl>
                                                    </VField>
                                                    <VField v-else>
                                                        <VControl icon="feather:bookmark">
                                                            <VInput type="text" class="input" :placeholder="data3.caption"
                                                                v-model="input[data3.model]" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'checkboxtextbox'">
                                                <div class="column is-12">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-10">
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[data3.model]"
                                                                        :true-value="data3.caption" :label="data3.caption"
                                                                        color="primary" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-8" style="margin-left:10px;margin-top:-20px">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput type="text" class="input"
                                                                        :placeholder="data3.caption"
                                                                        v-model="input[data3.model]" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'datetime'">
                                                <div class="column is-12">
                                                    <VField :label="data3.caption"></VField>
                                                    <VField addons>
                                                        <VDatePicker v-model="input[data3.model]" mode="dateTime"
                                                            style="width: 100%" trim-weeks :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue"
                                                                            :placeholder="data3.caption"
                                                                            v-on="inputEvents" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'time'">
                                                <VField :label="data3.caption"></VField>
                                                <VField addons>
                                                    <VDatePicker v-model="input[data3.model]" color="green" mode="time"
                                                        is24hr>
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VField>
                                                                <VControl>
                                                                    <VInput class="input form-timepicker"
                                                                        :value="inputValue" v-on="inputEvents" />
                                                                </VControl>
                                                            </VField>
                                                        </template>
                                                    </VDatePicker>
                                                </VField>
                                            </div>
                                            <div v-if="data3.type == 'textarea'">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabel>{{ data3.caption }}</VLabel>
                                                        <VControl>
                                                            <VTextarea v-model="input[data3.model]" rows="2"
                                                                :placeholder="data3.caption">
                                                            </VTextarea>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'combobox'">
                                                <div class="column is-12">
                                                    <VField :label="data3.caption"
                                                        class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                        <VControl icon="fa:search" fullwidth class="prime-auto ">
                                                            <AutoComplete v-model="input[data3.model]"
                                                                :suggestions="d_Combo[data3.id]"
                                                                @complete="fetchCombo($event, data3.id, data3.cbotable)"
                                                                :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                                :field="'label'" placeholder="ketik untuk mencari..." />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div v-if="data3.type == 'image'">
                                                <div class="column is-12">
                                                    <img :src="data3.caption" :style="data3.style" alt="Image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="listDynamic.tandatangan" class="column is-4" v-for="(data, index) in listDynamic.tandatangan"
                        :key="data.id">
                        <TandaTangan :elemenID="data" :width="'180'" :height="'180'"></TandaTangan>
                    </div>
                </div>

                <div class="columns is-multiline" v-else>
                    <div class="column is-12">
                        <div class="border-round border-1 surface-border p-4 surface-card">
                            <div class="flex mb-3">
                                <Skeleton shape="circle" size="4rem" class="mr-2"></Skeleton>
                                <div>
                                    <Skeleton width="10rem" class="mb-2"></Skeleton>
                                    <Skeleton width="5rem" class="mb-2"></Skeleton>
                                    <Skeleton height=".5rem"></Skeleton>
                                </div>
                            </div>
                            <Skeleton width="100%" height="150px"></Skeleton>
                            <Skeleton width="100%" height="100px" class="mt-2"></Skeleton>
                            <Skeleton width="100%" class="mt-2" v-for="index of 6"></Skeleton>
                            <div class="flex justify-content-between mt-3">
                                <Skeleton width="4rem" height="2rem"></Skeleton>
                                <Skeleton width="4rem" height="2rem"></Skeleton>
                            </div>
                        </div>
                    </div>
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
import AutoComplete from 'primevue/autocomplete';
import Skeleton from 'primevue/skeleton';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
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

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Combo: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const listDynamic: any = ref([])
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadDynamic = async () => {
    listDynamic.value = []
    await useApi().get(
        `/emr/get-emr-dynamic?collection=${COLLECTION.value}`).then((response: any) => {
            listDynamic.value = response
            item.classgrid = response.classgrid
        })
}
const loadRiwayat = async () => {
    // if (NOREC_EMRPASIEN.value == '') return
    await useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan 
                if (listDynamic.value.tandatangan) {
                    for (let x = 0; x < listDynamic.value.tandatangan.length; x++) {
                        const element = listDynamic.value.tandatangan[x];
                        H.tandaTangan().set(element, response[0][element])
                    }
                }
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value

    if (listDynamic.value.tandatangan) {
        for (let x = 0; x < listDynamic.value.tandatangan.length; x++) { 
            const element = listDynamic.value.tandatangan[x];
            object[element] = H.tandaTangan().get(element)
        }
    }

    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'name_form': props.FORM_NAME,
        'url_form': props.FORM_URL,
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
const fetchCombo = async (filter: any, id: any, link: any) => {
    const response = await useApi().get(
        `/emr/dropdown/${link}&limit=10&query=${filter.query}`)
    d_Combo.value[id] = response
}
const kembaliKeun = () => {
    window.history.back()
}
const setAutoFill = async () => {
    if (COLLECTION.value == 'PersetujuanRawatInap') {
        input.value.noRekamMedis = props.pasien.nocm
        input.value.nama = props.pasien.namapasien
        input.value.jenisKelamin = props.pasien.jeniskelamin
        input.value.tglPembuatan = new Date()
        input.value.petugasPerawat = {
            label: H.pegawaiLogin().namalengkap,
            value: H.pegawaiLogin().id

        }
    }

    // await useApi().get(
    //     "emr/auto-fill?nocmfk=" + ID_PASIEN +
    //     "&collection=VitalSign" +
    //     "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
    // ).then((response) => {

    //     input.value.beratBadan = response.beratBadan
    //     input.value.tinggiBadan = response.tinggiBadan
    //     input.value.IMT = response.IMT
    //     input.value.lingkarPerut = response.lingkarPerut
    //     input.value.tekananDarah = response.tekananDarah
    //     input.value.pernapasan = response.pernapasan
    //     input.value.suhu = response.suhu
    //     input.value.nadi = response.nadi
    // })
}

const init = () => {
    setView()
    loadDynamic()
    setAutoFill()
    loadRiwayat()
}

init()
</script>