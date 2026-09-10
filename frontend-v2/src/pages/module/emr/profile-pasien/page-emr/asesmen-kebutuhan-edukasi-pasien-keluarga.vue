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

    <div class="column is-12">
        <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
        <VCard v-else>
            <div class="column is-multiline">
                <h1 style="font-weight: bold;"></h1>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Nama (Name) : </h1>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Nama Lengkap"
                                        v-model="input.namaPasien" />
                                </VControl>

                            </VField>
                        </div>
                    </div>
                </div>
                 <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Umur (Age) : </h1>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Umur"
                                        v-model="input.umurPasien" />
                                </VControl>

                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Agama (Religion) : </h1>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Agama" v-model="input.agama" />
                                </VControl>

                            </VField>
                        </div>
                    </div>
                </div>
                 <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Alamat (Address) : </h1>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Alamat" v-model="input.alamatPasien" />
                                </VControl>

                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Tingkat Pendidikan (Level Of Education) : </h1>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Pendidikan" v-model="input.pendidikan" />
                                </VControl>

                            </VField>
                        </div>

                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Hubungan dengan Pasien (Relation with the Patient) : </h1>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Hubungan Dengan Pasien" v-model="input.Hubungan" />
                                </VControl>

                            </VField>
                        </div>
                    </div>
                </div>
                 <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Alasan (Reason) : </h1>
                        </div>
                        <div class="columns is-8" style=" margin-top: 1rem">
                            <VField v-for="items in alasan" :key="items.value" style="padding:0px;">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.alasan" class="pt-1 pb-1 "
                                        :true-value="items.label" :label="items.label" color="primary" square />
                                </VControl>
                            </VField>
                       </div>
                </div>
                <div class="column is-12 p-0">
                <div class="column is-4" style="margin-left :310px">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Lainnya"
                                        v-model="input.ketalasan" />
                                </VControl>

                            </VField>
                        </div>
                </div>
                <div class="column is-12 p-0 tg th">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Bicara (Speak) : </h1>
                        </div>
                        <div class="columns is-8" style=" margin-top: 1rem">
                            <VField v-for="items in bicara" :key="items.value" style="padding:0px;">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.bicara" class="pt-1 pb-1 "
                                        :true-value="items.label" :label="items.label" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12 p-0">
                <div class="column is-4" style="margin-left :310px">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.ketbicara" />
                                </VControl>

                            </VField>
                        </div>
                    </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Bahasa Sehari-hari (Daily Language) : </h1>
                        </div>
                        <div class="columns is-8" style=" margin-top: 1rem">
                            <VField v-for="items in bahasaHarian" :key="items.value" style="padding:0px;">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.bahasaHarian" class="pt-1 pb-1 "
                                        :true-value="items.label" :label="items.label" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                <div class="column is-12 p-0">
                <div class="column is-4" style="margin-left :310px">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.ketbahasaHarian" />
                                </VControl>

                            </VField>
                        </div>

                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Bahasa Isyarat (Sign Language) : </h1>
                        </div>
                        <div class="columns is-12" style=" margin-top: 1rem">
                            <VField v-for="items in bahasaIsyarat" :key="items.value" style="padding:0px;">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.bahasaIsyarat" class="pt-1 pb-1 " :true-value="items.label"
                                        :label="items.label" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold;">Hambatan Belajar (Learning Barriers) :</h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in hambatanBelajar">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                       </div>
                     <div class="column is-4">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.ketHambatanBelajar" />
                                </VControl>
                            </VField>
                    </div>
                </div>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold;">Cara Belajar Yang Disukai (Preferred Way of Learning) :</h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in caraBelajar">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                       </div>
                     <div class="column is-4">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.ketCaraBelajar" />
                                </VControl>
                            </VField>
                    </div>
                </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Kesediaan Pasien/Keluarga Menerima Informasi & Edukasi (Willingness to Receive Information and Education): </h1>
                        </div>
                        <div class="columns is-8" style=" margin-top: 1rem">
                            <VField v-for="items in kesediaanPasien" :key="items.value" style="padding:0px;">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.kesediaanPasien" class="pt-1 pb-1 " :true-value="items.label"
                                        :label="items.label" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetKesediaanPasien" />
                                </VControl>

                            </VField>
                        </div>
                    </div>
                </div>

                <h1 style="font-weight: bold;">B. Kebutuhan dan Rencana Informasi & Edukasi (Information & Education Needs and Planning) </h1>
                    <div class="column is-12">
                    <h1 style="font-weight: bold;">Medis (Medical) :</h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in medis">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                       </div>
                     <div class="column is-4">
                            <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetRencana" />
                            </VControl>
                    </div>
                </div>
                </div>
                <div class="column is-4 mt-3" style="margin-left: auto;" v-if="loadData == false">
                <div class="column pt-0">
                <VField>
                    <h1 style="font-weight: bold;">Nama Educator</h1>
                </VField>
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                        <AutoComplete v-model="input.educator1" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik nama petugas" />
                    </VControl>
                </VField>
                </div>
                </div>
                    <div class="column is-12">
                    <h1 style="font-weight: bold;">Manajemen Nyeri (Medis & Keperawatan) (Pain Management (Medical & Nursing) :</h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in medisKeperawatan">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                       </div>
                     <div class="column is-4">
                            <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetManajemenNyeri" />
                            </VControl>
                    </div>
                </div>
                </div>
                <div class="column is-4 mt-3" style="margin-left: auto;" v-if="loadData == false">
                <div class="column pt-0">
                <VField>
                    <h1 style="font-weight: bold;">Nama Educator</h1>
                </VField>
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                        <AutoComplete v-model="input.educator2" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik nama petugas" />
                    </VControl>
                </VField>
                </div>
                </div>

                 <div class="column is-12">
                    <h1 style="font-weight: bold;">Keperawatan (Nursing) :</h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in Keperawatan">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                       </div>
                     <div class="column is-4">
                            <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetKeperawatan" />
                            </VControl>
                    </div>
                </div>
                </div>
                <div class="column is-4 mt-3" style="margin-left: auto;" v-if="loadData == false">
                <div class="column pt-0">
                <VField>
                    <h1 style="font-weight: bold;">Nama Educator</h1>
                </VField>
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                        <AutoComplete v-model="input.educator3" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik nama petugas" />
                    </VControl>
                </VField>
                </div>
                </div>

                  <div class="column is-12">
                    <h1 style="font-weight: bold;">Pengobatan (Medication) :</h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in Pengobatan">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                       </div>
                     <div class="column is-4">
                            <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetPengobatan" />
                            </VControl>
                    </div>
                </div>
                </div>

                <div class="column is-4 mt-3" style="margin-left: auto;" v-if="loadData == false">
                <div class="column pt-0">
                <VField>
                    <h1 style="font-weight: bold;">Nama Educator</h1>
                </VField>
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                        <AutoComplete v-model="input.educator4" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik nama petugas" />
                    </VControl>
                </VField>
                </div>
                </div>

                  <div class="column is-12">
                    <h1 style="font-weight: bold;">Rohaniawan (Spiritual) :</h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in Rohaniawan">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                       </div>
                     <div class="column is-4">
                            <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetRohaniawan" />
                            </VControl>
                    </div>
                </div>
                </div>

                <div class="column is-4 mt-3" style="margin-left: auto;" v-if="loadData == false">
                <div class="column pt-0">
                <VField>
                    <h1 style="font-weight: bold;">Nama Educator</h1>
                </VField>
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                        <AutoComplete v-model="input.educator5" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik nama petugas" />
                    </VControl>
                </VField>
                </div>
                </div>

                  <div class="column is-12">
                    <h1 style="font-weight: bold;">Nutrisionis :</h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in Nutrisionis">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                       </div>
                     <div class="column is-4">
                            <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetNutrisionis" />
                            </VControl>
                    </div>
                </div>
                </div>

                <div class="column is-4 mt-3" style="margin-left: auto;" v-if="loadData == false">
                <div class="column pt-0">
                <VField>
                    <h1 style="font-weight: bold;">Nama Educator</h1>
                </VField>
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                        <AutoComplete v-model="input.educator6" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik nama petugas" />
                    </VControl>
                </VField>
                </div>
                </div>

                <div class="column is-12">
                    <h1 style="font-weight: bold;">Rehabilitasi Medis (Medical Rehabilitation)(Dokter Sp.KFR, Fisioterapi, Okupasiterapi,Terapi Wicara, Ortotik Prostotik, Psikologi, Pekerja Sosial Medik) :</h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in medisKeperawatan">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                       </div>
                     <div class="column is-4">
                            <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetRehabilitasi" />
                            </VControl>
                    </div>
                </div>
                </div>

                <div class="column is-4 mt-3" style="margin-left: auto;" v-if="loadData == false">
                <div class="column pt-0">
                <VField>
                    <h1 style="font-weight: bold;">Nama Educator</h1>
                </VField>
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                        <AutoComplete v-model="input.educator7" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik nama petugas" />
                    </VControl>
                </VField>
                </div>
                </div>

                  <div class="column is-12">
                    <h1 style="font-weight: bold;">Informasi Lainnya (Other Information) :</h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in Informasi">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                       </div>
                     <div class="column is-4">
                            <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetInformasiLainnya" />
                            </VControl>
                    </div>
                </div>
                </div>

                <div class="column is-4 mt-3" style="margin-left: auto;" v-if="loadData == false">
                <div class="column pt-0">
                <VField>
                    <h1 style="font-weight: bold;">Nama Educator</h1>
                </VField>
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                        <AutoComplete v-model="input.educator8" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik nama petugas" />
                    </VControl>
                </VField>
                </div>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold;">Metode Edukasi (Educational Method) </h1>
                     <div class="column is-6">
                            <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetEdukasi" />
                            </VControl>
                    </div>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold;">Waktu Edukasi (Educational Time) :</h1>
                     <div class="column is-6">
                <VField>
                    <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                            <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Jam Keluar IGD" v-on="inputEvents" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                </VField>
                </div>
                </div>
            </div>
        </VCard>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/asesmen-kebutuhan-edukasi-pasien-keluarga'



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let caraBelajar = ref(EMR.caraBelajar())
let BidangDisiplin = ref(EMR.BidangDisiplin())
let hambatanBelajar = ref(EMR.hambatanBelajar())
let medis = ref(EMR.medis())
let medisKeperawatan = ref(EMR.medisKeperawatan())
let Keperawatan = ref(EMR.Keperawatan())
let Pengobatan = ref(EMR.Pengobatan())
let Nutrisionis = ref(EMR.Nutrisionis())
let RehabilitasiMedis = ref(EMR.RehabilitasiMedis())
let Informasi = ref(EMR.Informasi())
let Rohaniawan = ref(EMR.Rohaniawan())


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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const d_Petugas: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('KebutuhanEdukasiKeluarga')  //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: new Date(),
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

console.log('form_name', props.FORM_NAME)

const kesulitanKomunikasi: any = ref([
    { label: 'Tidak Ada', value: 'Tidak Ada' },
    { label: 'Ada, Jelaskan', value: 'Ada' }
])
const alasan: any = ref([
    { label: 'Penurunan Kesadaran (Loss of Consciousness)', value: 'Penurunan Kesadaran (Loss of Consciousness)' },
    { label: 'Lainnya (Etc.)', value: 'Lainnya' }
])
const bicara: any = ref([
    { label: 'Normal', value: 'Normal' },
    { label: 'Serangan awal gangguan bicara (Speech Disorder), Kapan', value: 'Serangan awal gangguan bicara (Speech Disorder),' }
])
const bahasaHarian: any = ref([
    { label: 'Indonesia', value: 'Indonesia' },
    { label: 'Inggris', value: 'Inggris' },
    { label: 'Daerah', value: 'Mandarin' },
    { label: 'Lain-Lain, Jelaskan', value: 'Bahasa Lainnya' },
])
const bahasaIsyarat: any = ref([
    { label: 'Ya', value: 'Ya' },
    { label: 'Tidak', value: 'Tidak' },
])
const bahasa: any = ref([
    { label: 'Indonesia', value: 'Indonesia' },
    { label: 'Inggris', value: 'Inggris' },
    { label: 'Mandarin', value: 'Mandarin' },
    { label: 'Bahasa Lainnya', value: 'Bahasa Lainnya' },
])
const penterjemah: any = ref([
    { label: 'Tidak Perlu', value: 'Tidak Perlu' },
    { label: 'Perlu,', value: 'Perlu' },
    { label: 'Lainnya, Jelaskan', value: 'Lainnya' }
])
const kesediaanPasien: any = ref([
    { label: 'Ya', value: 'Ya' },
    { label: 'Tidak,', value: 'Tidak' },
])
const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
}
const loadRiwayat = async () => {
    // if (NOREC_EMRPASIEN.value == '') return
    let response = await useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
    } else {
    isLoading.value = true
    const responseTglRuangan = await useApi().get(`/emr/get-emr-tgl-terakhir-dengan-ruangan?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
    const responseHistori = await useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
    isLoading.value = false
    if (responseTglRuangan.length && responseHistori.length) {
        console.log("Ruangan dulu : " + responseTglRuangan[0].registrasi.namaruangan)
        var tgl_EMR_terakhir = moment(responseTglRuangan[0].created_at).format("DD-MM-YYYY");
        var convertTgl = moment(tgl_EMR_terakhir, 'DD-MM-YYYY');
        var tgl_Sekarang = moment();
        isLoading.value = false
        const calculateDays = tgl_Sekarang.diff(convertTgl, 'days');
        if (responseTglRuangan[0].registrasi.namaruangan.trim() == H.setObjectRegistrasi(pasien.value.registrasi).namaruangan.trim() && calculateDays < 90) {
            confirm.require({
            message: 'Asesmen Kebutuhan Edukasi Pasien Keluarga sudah pernah diinput ' + calculateDays + ' hari dari tanggal registrasi pasien ini. Apakah anda ingin melihat riwayat terakhirnya ?',
            group: 'templating',
            header: 'Asesmen Kebutuhan Edukasi Pasien Keluarga',
            icon: 'pi pi-exclamation-circle',
            accept: () => {
                if (responseHistori.length) {
                input.value = responseHistori[0] //set ke inputan
                input.value.namatemplate = ''
                // console.log(input.value)
                isLoading.value = false // Set isLoading to false after loading history data
                } else {
                H.alert('warning', 'Data tidak ada')
                isLoading.value = false
                }
            },
            reject: () => {
                isLoading.value = false // Ensure isLoading is set to false if rejected
            }
            })
        }
        } else {
        console.log('Data EMR sebelumnya tidak ada!')
        isLoading.value = false // Set isLoading to false when no previous data is found
        }
        console.log("Ruangan pasien sekarang : " +props.registrasi.namaruangan)
    }

}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
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

const kembaliKeun = () => {
    window.history.back()
}
const setAutoFill = async () => {

    input.value.namaPasien = props.pasien.namapasien
    input.value.jenisKelaminPasien = props.pasien.jeniskelamin
    input.value.norm = props.pasien.nocm
    input.value.agama = props.pasien.agama
    input.value.pendidikan = props.pasien.pendidikan
    input.value.tanggalLahirPasien = props.pasien.tgllahir
    input.value.alamatPasien = props.pasien.alamatlengkap
    input.value.dokterRawat = props.registrasi.dokter
    input.value.tglPembuatan = new Date()
}
setView()
setAutoFill()
loadRiwayat()


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
</script>
<style lang="scss">
#signature {
    border: double 3px transparent;
    border-radius: 5px;
    background-image: linear-gradient(white, white),
        radial-gradient(circle at top left, #4bc5e8, #9f6274);
    background-origin: border-box;
    background-clip: content-box, border-box;
}

.container {
    width: "100%";
    padding: 8px 16px;
}

.p-fieldsets .p-fieldset-content {
    background: #ffffff;
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
}
</style>
