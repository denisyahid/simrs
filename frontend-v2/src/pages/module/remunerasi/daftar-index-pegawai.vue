<template>
    <section>

        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Daftar Pegawai</label>
                        <label>Perhitungan Index Pegawai</label>
                    </div>
                </div>

                <div class="column is-12 mt-3">
                    <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                    <DataTable v-else :value="dataSource" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="loadSearch"
                        class="p-datatable-sm" breakpoint="960px" selectionMode="single" sortMode="multiple"
                        v-model:expanded-rows="expandedRows" tableStyle="min-width: 30rem" showGridlines
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                        <template #header>
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <VField class=" is-rounded-select is-autocomplete-select" label="Unit">
                                        <VControl icon="feather:search" class="prime-auto">
                                            <Dropdown v-model="item.sunitkerja" :options="d_Unit" :optionLabel="'label'"
                                                placeholder="Pilih Unit" :optionValue="'value'" style="width: 100%;"
                                                :filter="true" appendTo="body" showClear />
                                        </VControl>
                                    </VField>
                                    <!-- <VField class="is-autocomplete-select">
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.sunitkerja" :options="d_Unit"
                                                placeholder="Pilih Unit" :searchable="true" />
                                        </VControl>
                                    </VField> -->
                                </div>
                                <div class="column is-5">
                                    <VField class="hide-margin mb-3" label="Pegawai">
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="item.search" :suggestions="d_Pegawai" class="mt-2"
                                                :optionLabel="'label'" @complete="dropPegawai($event)" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" dropPegawai
                                                :field="'label'" placeholder="Pilih Pegawai" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-1 btn-search pt-0 mt-5">
                                    <VIconButton color="success" icon="fas fa-search" @click="fetchPegawai"
                                        :loading="loadSearch" />
                                </div>
                            </div>
                        </template>
                        <Column :exportable="false" header="Action" style="min-width: 10px;" frozen>
                            <template #body="slotProps">
                                <VButtons style="display: flex !important;justify-content: space-evenly !important;">
                                    <VIconButton color="primary" outlined circle icon="fas fa-pen"
                                        @click="editPegawai(slotProps.data)" v-tooltip.rounded="'Edit'" />
                                    <!-- <VIconButton color="danger" outlined circle icon="fas fa-sitemap"
                                        v-tooltip.right.rounded="'Map Pegawai'" /> -->
                                </VButtons>
                            </template>
                        </Column>
                        <Column field="namalengkap" header="Nama" style="min-width: 250px;" frozen />
                        <Column field="namajabatan" header="Jabatan" style="min-width: 200px;" />
                        <Column field="golongan" style="min-width: 100px;" header="Golongan" />
                        <Column field="pendidikan" style="min-width: 100px;" header="Pendidikan" />
                        <Column field="unitkerja" style="min-width: 200px;" header="Unit Kerja" />
                        <Column field="namaruangan" style="min-width: 250px;" header="Sub Unit Kerja" />
                        <Column field="poinjpu" style="min-width: 80px;" header="Poin JPU" />
                        <Column field="idxbasic" style="min-width: 80px;" header="Basic Index" />
                        <Column field="idxcompetency" style="min-width: 80px;" header="Competency" />
                        <Column field="idxrisk" style="min-width: 80px;" header="Risk Index" />
                        <Column field="idxposition" style="min-width: 80px;" header="Position Index" />
                        <Column field="totalindex" style="min-width: 80px;" header="Total Index" />
                        <Column field="pointcasemix" style="min-width: 80px;" header="Poin Casemix" />
                    </DataTable>
                </div>
            </VCard>
        </div>

        <VModal :open="modalEdit" title="Detail" size="large" actions="right" @close="modalEdit = false"
            cancelLabel="Tutup">
            <template #content>
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <VField label="Nama Pegawai">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.namapegawai" placeholder="Nama Pegawai" disabled />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField>
                            <VLabel>Tanggal Masuk</VLabel>
                            <VDatePicker v-model="item.tglmasuk" mode="dateTime" style="width: 100%" trim-weeks>
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
                    <div class="column is-3">
                        <VField label="NIP">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.nip" placeholder="Nama Pegawai" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VField class="is-autocomplete-select" label="Jabatan">
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.jabatanfk" :options="d_Jabatan"
                                    placeholder="Pilih Jabatan" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField class="is-autocomplete-select" label="Golongan">
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.golonganfk" :options="d_Golonganpegawai"
                                    placeholder="Pilih Golongan" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField class="is-autocomplete-select" label="Pendidikan">
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.pendidikanfk" :options="d_Pendidikan"
                                    placeholder="Pilih Pendidikan" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField class="is-autocomplete-select" label="Unit Kerja">
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.unitfk" :options="d_Unit" placeholder="Pilih Unit"
                                    :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline pt-2">
                    <div class="column is-6">
                        <VField class="is-autocomplete-select" label="Sub Unit Kerja">
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.subunitkerjafk" :options="d_ruangan"
                                    placeholder="Pilih Sub Unit Kerja" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="No Rekening">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.noRekening" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline pt-2">
                    <div class="column is-3">
                        <VField label="Basic index">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.basicIndex" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Competency index">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.competencyIndex" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Risk index">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.riskIndex" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Emergency index">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.emergencyIndex" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline pt-2">
                    <div class="column is-3">
                        <VField label="Position Index">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.positionIndex" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Point JPU">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.pointJpu" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Total index">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.totalIndex" disabled />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Point Casemix">
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.pointcasemix" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </template>
            <template #action>
                <VButton icon="feather:plus" color="primary" :loading="isLoadSave" @click="savePegawai(item)" raised>Simpan
                </VButton>
            </template>
        </VModal>
    </section>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import Dropdown from 'primevue/dropdown';
import DataTable from 'primevue/datatable'
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import Dialog from 'primevue/dialog';
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Daftar Index Pegawai - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const router = useRouter()
const confirm = useConfirm()
const dataSource: any = ref([])
const detailResep: any = ref([])
const op = ref();
const selected: any = ref({})
const isLoadSave: any = ref(false)
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
const expandedRows = ref();
const d_Pegawai: any = ref([])
let d_ruangan: any = ref([])
let d_Unit: any = ref([])
let d_Golonganpegawai: any = ref([])
let d_Pendidikan: any = ref([])
let d_Jabatan: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(true)

const fetchPegawai = async () => {

    loadSearch.value = true
    let namapegawai = item.value.search ? item.value.search.value : ''
    let unitKerja = item.value.sunitkerja ? item.value.sunitkerja : ''
    await useApi().get(`remunerasi/get-daftar-index-pegawai?unitfk=${ unitKerja }&pegawaifk=${namapegawai}`).then((response) => {
        dataSource.value = response
    })
    loadData.value = false
    loadSearch.value = false
}

const fetchUnit = async () => {
    useApi().get('remunerasi/get-combo-idx').then((response) => {
        d_Unit.value = response.unitkerja.map((e: any) => {
            return { label: e.unitkerja, value: e.id }
        })
        d_ruangan.value = response.ruangan.map((e: any) => {
            return { label: e.namaruangan, value: e.id }
        })
        d_Golonganpegawai.value = response.golonganpegawai.map((e: any) => {
            return { label: e.golongan, value: e.id }
        })
        d_Pendidikan.value = response.pendidikan.map((e: any) => {
            return { label: e.pendidikan, value: e.id }
        })
        d_Jabatan.value = response.jabatan.map((e: any) => {
            return { label: e.namajabatan, value: e.id }
        })
    })
}

const savePegawai = async (e: any) => {
    isLoadSave.value = true
    let objSave = {
        'id': e.id,
        'tglmasuk': e.tglmasuk ? H.formatDate(e.tglmasuk, 'YYYY-MM-DDTHH:mm:ss') : null,
        'nip': e.nip,
        'pendidikanfk': e.pendidikanfk,
        'golonganfk': e.golonganfk,
        'jabatanfk': e.jabatanfk,
        'unitfk': e.unitfk,
        'subunitkerjafk': e.subunitkerjafk,
        'noRekening': e.noRekening,
        'basicIndex': e.basicIndex,
        'emergencyIndex': e.emergencyIndex,
        'competencyIndex': e.competencyIndex,
        'riskIndex': e.riskIndex,
        'positionIndex': e.positionIndex,
        'pointJpu': e.pointJpu,
        'totalIndex': e.totalIndex,
        'pointcasemix': e.pointcasemix
    }
    await useApi().post('remunerasi/update-index-pegawai', objSave)
    fetchPegawai()
    modalEdit.value = false
    isLoadSave.value = false
}

const dropPegawai = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

const editPegawai = (e: any) => {
    console.log(e)
    modalEdit.value = true
    item.value.id = e.pgid
    item.value.namapegawai = e.namalengkap
    item.value.tglmasuk = e.tglmasuk
    item.value.nip = e.nip
    item.value.pendidikanfk = e.objectpendidikanterakhirfk
    item.value.golonganfk = e.objectgolonganfk
    item.value.jabatanfk = e.objectjabatanfungsionalfk
    item.value.unitfk = e.objectunitkerjapegawaifk
    item.value.subunitkerjafk = e.objectruangankerjafk
    item.value.noRekening = e.nomorrekening
    item.value.basicIndex = e.idxbasic
    item.value.emergencyIndex = e.idxemergency
    item.value.competencyIndex = e.idxcompetency
    item.value.riskIndex = e.idxrisk
    item.value.positionIndex = e.idxposition
    item.value.ponitJpu = e.poinjpu
    item.value.totalIndex = e.totalindex
    item.value.pointcasemix = e.pointcasemix
    // console.log(e)
}


fetchUnit()
fetchPegawai()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 0px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
    margin-top: 23px;
}

.hide-margin {
    .label {
        margin-bottom: 0px;
    }
}
</style>
