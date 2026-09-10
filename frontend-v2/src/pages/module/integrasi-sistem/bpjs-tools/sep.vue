<template>
    <ConfirmDialog />
    <h1>Pembuatan SEP</h1>
    <div v-for="(data, index) in items" >
        <div class="s-card" :key="index">
            <div class="card-head">
                <VBlock :title="data.title" center @click="toggleCollapse(index, items)">
                    <template #action>
                        <VIconButton outlined circle icon="feather:chevron-right" v-if="!data.isExpanded" />
                        <VIconButton outlined circle icon="feather:chevron-down" v-else="data.isExpanded" />
                    </template>
                </VBlock>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Cari SEP'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <VLabel>No SEP</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="No SEP" v-model="item.nosep" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="cariSEP()" icon="feather:search" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonCariSEP }}</code>
                    </pre>  
                </div>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Hapus SEP'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <VLabel>No SEP</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="No SEP" v-model="item.delnosep" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="danger" @click="DialogConfirm()" icon="feather:trash-2" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonHapusSEP }}</code>
                    </pre>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-5"></div>
    <!-- batas nya  -->
    <h1>Potensi Suplesi Jasa Raharja</h1>
    <div v-for="(data, index) in items2" >
        <div class="s-card" :key="index">
            <div class="card-head">
                <VBlock :title="data.title" center @click="toggleCollapse(index, items2)">
                    <template #action>
                        <VIconButton outlined circle icon="feather:chevron-right" v-if="!data.isExpanded" />
                        <VIconButton outlined circle icon="feather:chevron-down" v-else="data.isExpanded" />
                    </template>
                </VBlock>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Suplesi Jasa Raharja'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <VLabel>No Kartu</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="No Kartu" v-model="item.nokartu" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VDatePicker v-model="item.tglsep" color="green" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                        <VField>
                            <VLabel>Tanggal pelayanan/SEP</VLabel>
                            <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </VField>
                        </template>
                    </VDatePicker>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="SupJR()" icon="feather:search" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonSupJR }}</code>
                    </pre>  
                </div>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Data Induk Kecelakaan'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <VLabel>No Kartu</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="No Kartu" v-model="item.nokartu" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="DataIndukKec()" icon="feather:search" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonDataIndukKec }}</code>
                    </pre>  
                </div>
            </div>
        </div>
    </div>
    <div class="mb-5"></div>
    <!-- batas nya  -->
    <h1>Approval Penjaminan SEP</h1>
    <div v-for="(data, index) in items3" >
        <div class="s-card" :key="index">
            <div class="card-head">
                <VBlock :title="data.title" center @click="toggleCollapse(index, items3)">
                    <template #action>
                        <VIconButton outlined circle icon="feather:chevron-right" v-if="!data.isExpanded" />
                        <VIconButton outlined circle icon="feather:chevron-down" v-else="data.isExpanded" />
                    </template>
                </VBlock>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Pengajuan'">
            <div class="columns is-multiline">
                <div class="column is-6">
                    <VField>
                        <VLabel>No Kartu</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="No Kartu" v-model="item.nokartu" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VDatePicker v-model="item.tglsep" color="green" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                        <VField>
                            <VLabel>Tanggal Penerbitan SEP</VLabel>
                            <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </VField>
                        </template>
                    </VDatePicker>
                </div>
                <div class="column is-6">
                    <VField>
                        <VLabel>Jenis Pelayanan</VLabel>
                        <VControl>
                            <VRadio v-model="item.jnspelayanan" value="1" label="Rawat Inap" name="jnspelayanan" color="primary" />
                            <VRadio v-model="item.jnspelayanan" value="2" label="Rawat Jalan" name="jnspelayanan" color="primary" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VLabel>Jenis Pengajuan</VLabel>
                        <VControl>
                            <VRadio v-model="item.jnspengajuan" value="1" label="Back Date" name="jnspengajuan" color="primary" />
                            <VRadio v-model="item.jnspengajuan" value="2" label="Finger Print" name="jnspengajuan" color="primary" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField>
                        <VLabel>Keterangan</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="Keterangan" v-model="item.keterangan" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="pengajuanSEP()" icon="feather:send" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonPengajuanSEP }}</code>
                    </pre>
                </div>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Aproval Pengajuan SEP'">
            <div class="columns is-multiline">
                <div class="column is-6">
                    <VField>
                        <VLabel>No Kartu</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="No Kartu" v-model="item.nokartu" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VDatePicker v-model="item.tglsep" color="green" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                        <VField>
                            <VLabel>Tanggal Penerbitan SEP</VLabel>
                            <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </VField>
                        </template>
                    </VDatePicker>
                </div>
                <div class="column is-6">
                    <VField>
                        <VLabel>Jenis Pelayanan</VLabel>
                        <VControl>
                            <VRadio v-model="item.jnspelayanan" value="1" label="Rawat Inap" name="jnsapprovalpelayanan" color="primary" />
                            <VRadio v-model="item.jnspelayanan" value="2" label="Rawat Jalan" name="jnsapprovalpelayanan" color="primary" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VLabel>Jenis Pengajuan</VLabel>
                        <VControl>
                            <VRadio v-model="item.jnspengajuan" value="1" label="Back Date" name="jnsapprovalpengajuan" color="primary" />
                            <VRadio v-model="item.jnspengajuan" value="2" label="Finger Print" name="jnsapprovalpengajuan" color="primary" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField>
                        <VLabel>Keterangan</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="Keterangan" v-model="item.keterangan" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="approvalPengajuanSEP()" icon="feather:save" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonApprovalPengajuanSEP }}</code>
                    </pre>
                </div>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'List Data Persetujuan SEP'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField class=" is-rounded-select is-autocomplete-select">
                        <VLabel>Bulan</VLabel>
                        <VControl icon="feather:calendar" class="prime-auto">
                        <Dropdown v-model="item.bulan" :options="d_bulans" :optionLabel="'namabulan'" style="width: 100%;" :filter="true" appendTo="body" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField class=" is-rounded-select is-autocomplete-select">
                        <VLabel>Tahun</VLabel>
                        <VControl icon="feather:calendar" class="prime-auto">
                        <Dropdown v-model="item.tahun" :options="d_tahuns" :optionLabel="'tahun'" style="width: 100%;" :filter="true" appendTo="body" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="listPengajuanSEP()" icon="feather:search" :loading="isLoadingCall" />
                    </VButtons>
                </div>

                <div class="column is-12">
                    <DataTable :value="dataSourcefiltered" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

                        <Column field="noKartu" header="No Kartu"   :sortable="true"></Column>
                        <Column field="nama" header="Nama" :sortable="true"></Column>
                        <Column field="tglsep" header="Tgl SEP" :sortable="true"></Column>
                        <Column field="jnspelayanan" header="Jenis Pelayanan" :sortable="true"></Column>
                        <Column field="persetujuan" header="Persetujuan" :sortable="true"></Column>
                        <Column field="status" header="Status" :sortable="true"></Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-5"></div>
    <!-- batas nya  -->
    <h1>Update Tgl Pulang SEP</h1>
    <div v-for="(data, index) in items4" >
        <div class="s-card" :key="index">
            <div class="card-head">
                <VBlock :title="data.title" center @click="toggleCollapse(index, items4)">
                    <template #action>
                        <VIconButton outlined circle icon="feather:chevron-right" v-if="!data.isExpanded" />
                        <VIconButton outlined circle icon="feather:chevron-down" v-else="data.isExpanded" />
                    </template>
                </VBlock>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Update Tanggal Pulang'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField>
                        <VLabel>No SEP</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="No SEP" v-model="item.nosep" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField class=" is-rounded-select is-autocomplete-select">
                        <VLabel>Status Pulang</VLabel>
                        <VControl icon="feather:calendar" class="prime-auto">
                        <Dropdown v-model="item.statuspulang" :options="d_statuspulangs" :optionLabel="'statuspulang'" style="width: 100%;" :filter="true" appendTo="body" @change="showMeninggal(item.statuspulang)" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VDatePicker v-model="item.tglpulang" color="green" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                        <VField>
                            <VLabel>Tanggal Pulang</VLabel>
                            <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </VField>
                        </template>
                    </VDatePicker>
                </div>
                <div class="column is-4" v-if="isFormMeninngal">
                    <VDatePicker v-model="item.tglmeninggal" color="green" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                        <VField>
                            <VLabel>Tanggal Meninggal</VLabel>
                            <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </VField>
                        </template>
                    </VDatePicker>
                </div>
                <div class="column is-4" v-if="isFormMeninngal">
                    <VField>
                        <VLabel>No Surat Meninggal</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="No Surat Meninggal" v-model="item.nosuratmeninggal" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VLabel>No LP Manual</VLabel>
                        <VControl>
                            <VInput type="text" placeholder="Diisi jika SEP KKL" v-model="item.nolpmanual" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="UpdateTglpulangSEP()" icon="feather:save" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonUpdateTglpulangSEP }}</code>
                    </pre>
                </div>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'List Data Update Tanggal Pulang'">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField class=" is-rounded-select is-autocomplete-select">
                        <VLabel>Bulan</VLabel>
                        <VControl icon="feather:calendar" class="prime-auto">
                        <Dropdown v-model="item.bulan" :options="d_bulans" :optionLabel="'namabulan'" style="width: 100%;" :filter="true" appendTo="body" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField class=" is-rounded-select is-autocomplete-select">
                        <VLabel>Tahun</VLabel>
                        <VControl icon="feather:calendar" class="prime-auto">
                        <Dropdown v-model="item.tahun" :options="d_tahuns" :optionLabel="'tahun'" style="width: 100%;" :filter="true" appendTo="body" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top:24px">
                    <VButtons>
                        <VIconButton color="success" @click="listTglPulangSEP()" icon="feather:search" :loading="isLoadingCall" />
                    </VButtons>
                </div>
                <div class="column is-12">
                    <pre class="left shiki min-light" tabindex="0" style="background-color: rgb(255, 255, 255);">
<code>{{ item.jsonlistTglPulangSEP }}</code>
                    </pre>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-5"></div>
    <!-- batas nya  -->
    <h1>Integrasi SEP dan Inacbg</h1>
    <div v-for="(data, index) in items5" >
        <div class="s-card" :key="index">
            <div class="card-head">
                <VBlock :title="data.title" center @click="toggleCollapse(index, items5)">
                    <template #action>
                        <VIconButton outlined circle icon="feather:chevron-right" v-if="!data.isExpanded" />
                        <VIconButton outlined circle icon="feather:chevron-down" v-else="data.isExpanded" />
                    </template>
                </VBlock>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Integrasi SEP dengan Inacbg'">
            <div class="columns is-multiline">
            </div>
        </div>
    </div>
    <div class="mb-5"></div>
    <!-- batas nya  -->
    <h1>SEP Internal</h1>
    <div v-for="(data, index) in items6" >
        <div class="s-card" :key="index">
            <div class="card-head">
                <VBlock :title="data.title" center @click="toggleCollapse(index, items6)">
                    <template #action>
                        <VIconButton outlined circle icon="feather:chevron-right" v-if="!data.isExpanded" />
                        <VIconButton outlined circle icon="feather:chevron-down" v-else="data.isExpanded" />
                    </template>
                </VBlock>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Data SEP Internal'">
            <div class="columns is-multiline">
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Hapus SEP Internal'">
            <div class="columns is-multiline">
            </div>
        </div>
    </div>
    <div class="mb-5"></div>
    <!-- batas nya  -->
    <h1>Finger Print</h1>
    <div v-for="(data, index) in items7" >
        <div class="s-card" :key="index">
            <div class="card-head">
                <VBlock :title="data.title" center @click="toggleCollapse(index, items7)">
                    <template #action>
                        <VIconButton outlined circle icon="feather:chevron-right" v-if="!data.isExpanded" />
                        <VIconButton outlined circle icon="feather:chevron-down" v-else="data.isExpanded" />
                    </template>
                </VBlock>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Get Finger Print'">
            <div class="columns is-multiline">
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Get List Finger Print'">
            <div class="columns is-multiline">
            </div>
        </div>
    </div>
    <div class="mb-5"></div>
    <!-- batas nya  -->
    <h1>Random Question</h1>
    <div v-for="(data, index) in items8" >
        <div class="s-card" :key="index">
            <div class="card-head">
                <VBlock :title="data.title" center @click="toggleCollapse(index, items8)">
                    <template #action>
                        <VIconButton outlined circle icon="feather:chevron-right" v-if="!data.isExpanded" />
                        <VIconButton outlined circle icon="feather:chevron-down" v-else="data.isExpanded" />
                    </template>
                </VBlock>
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Get Random Question'">
            <div class="columns is-multiline">
            </div>
        </div>
        <div class="card-inner" v-show="data.isExpanded" v-if="data.title == 'Post Random Answer'">
            <div class="columns is-multiline">
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Dropdown from 'primevue/dropdown';

import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useToaster } from '/@src/composable/toaster'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'

const setView = () => {
    useHead({
        title: 'SEP - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

const confirm = useConfirm()
const isLoadingCall = ref(false)
const isFormMeninngal = ref(false)
let item: any = reactive({})
let dataSource: any = ref([])
const d_bulans = ref([])
const d_tahuns = ref([])
const d_statuspulangs = ref([])
const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.namaruangan.match(new RegExp(filters.value, 'i'))
    )
  })
})

d_bulans.value = [
    { id: 1, namabulan: "Januari" },
    { id: 2, namabulan: "Februari" },
    { id: 3, namabulan: "Maret" },
    { id: 4, namabulan: "April" },
    { id: 5, namabulan: "Mei" },
    { id: 6, namabulan: "Juni" },
    { id: 7, namabulan: "Juli" },
    { id: 8, namabulan: "Agustus" },
    { id: 9, namabulan: "September" },
    { id: 10, namabulan: "Oktober" },
    { id: 11, namabulan: "November" },
    { id: 12, namabulan: "Desember" },
]
for (let i = H.formatDate(new Date(),'YYYY') - 30; i <= H.formatDate(new Date(),'YYYY'); i++) {
    d_tahuns.value.push({
        id: i, tahun: i
    })
}
d_statuspulangs.value = [
    { id: 1, statuspulang: 'Atas Persetujuan Dokter' },
    { id: 3, statuspulang: 'Atas Permintaan Sendiri' },
    { id: 4, statuspulang: 'Meninggal' },
    { id: 5, statuspulang: 'Lain-lain' },
]
// Pembuatan SEP
const items = reactive([
    { title: 'Cari SEP', isExpanded: false },
    { title: 'Hapus SEP', isExpanded: false },
])
// Potensi Suplesi Jasa Raharja
const items2 = reactive([
    { title: 'Suplesi Jasa Raharja', isExpanded: false },
    { title: 'Data Induk Kecelakaan', isExpanded: false },
])
// Approval Penjaminan SEP
const items3 = reactive([
    { title: 'Pengajuan', isExpanded: false },
    { title: 'Aproval Pengajuan SEP', isExpanded: false },
    { title: 'List Data Persetujuan SEP', isExpanded: false },
])
// Update Tgl Pulang SEP
const items4 = reactive([
    { title: 'Update Tanggal Pulang', isExpanded: false },
    { title: 'List Data Update Tanggal Pulang', isExpanded: false },
])
// Integrasi SEP dan Inacbg
const items5 = reactive([
    { title: 'Integrasi SEP dengan Inacbg', isExpanded: false },
])
// SEP Internal
const items6 = reactive([
    { title: 'Data SEP Internal', isExpanded: false },
    { title: 'Hapus SEP Internal', isExpanded: false },
])
// Finger Print
const items7 = reactive([
    { title: 'Get Finger Print', isExpanded: false },
    { title: 'Get List Finger Print', isExpanded: false },
])
// Random Question
const items8 = reactive([
    { title: 'Get Random Question', isExpanded: false },
    { title: 'Post Random Answer', isExpanded: false },
])

const toggleCollapse = (index, array) => {
    for (let i = 0; i < array.length; i++) {
        if (i === index) {
            array[i].isExpanded = !array[i].isExpanded;
        } else {
            array[i].isExpanded = false;
        }
    }
}
const showMeninggal = (e) => {
    if(e.id == 4) {
        isFormMeninngal.value = true
    } else {
        isFormMeninngal.value = false
    }
}

const cariSEP = async () => {
    if(!item.nosep) {
        useToaster().error('Harap isi No SEP terlebih dahulu !')
        return
    }

    let json = {
        "url": `SEP/${item.nosep}`,
        "method": "GET",
        "data": null
    }
    
    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
        isLoadingCall.value = false
        item.jsonCariSEP = response
    })
}
const DialogConfirm = () => {
    if (!item.delnosep) {
        useToaster().error('Harap isi No SEP terlebih dahulu !')
        return
    }

    confirm.require({
        message: 'Apakah anda serius menghapus data SEP ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            hapusSEP()

        },
        reject: () => { },
    })
}
const hapusSEP = async () => {
    if (!item.delnosep) {
        useToaster().error('Harap isi No SEP terlebih dahulu !')
        return
    }

    let json = {
        "url": `SEP/2.0/delete`,
        "method": "DELETE",
        "data": {
            "request": {
                "t_sep": {
                    "noSep": item.delnosep,
                    "user": H.namaPegawai()
                }
            }
        }
    }

    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
            isLoadingCall.value = false
            item.jsonHapusSEP = response
        })
}
const pengajuanSEP = async () => {
    if (!item.nokartu) {
        useToaster().error('Harap isi No Kartu terlebih dahulu !')
        return
    }

    if (!item.tglsep) {
        useToaster().error('Harap isi Tgl SEP terlebih dahulu !')
        return
    }

    if (!item.jnspelayanan) {
        useToaster().error('Harap pilih Jenis Pelayanan terlebih dahulu !')
        return
    }
    
    if (!item.jnspengajuan) {
        useToaster().error('Harap pilih Jenis Pengajuan terlebih dahulu !')
        return
    }

    if (!item.keterangan) {
        useToaster().error('Harap isi Keterangan terlebih dahulu !')
        return
    }

    let json = {
        "url": `Sep/pengajuanSEP`,
        "method": "POST",
        "data": {
           "request": {
              "t_sep": {
                 "noKartu": item.nokartu,
                 "tglSep": H.formatDate(item.tglsep,'YYYY-MM-DD'),
                 "jnsPelayanan": item.jnspelayanan,
                 "jnsPengajuan": item.jnspengajuan,
                 "keterangan": item.keterangan,
                 "user": H.namaPegawai()
              }
           }
        }
    }

    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
            isLoadingCall.value = false
            item.jsonPengajuanSEP = response
        })
}
const approvalPengajuanSEP = async () => {
    if (!item.nokartu) {
        useToaster().error('Harap isi No Kartu terlebih dahulu !')
        return
    }

    if (!item.tglsep) {
        useToaster().error('Harap isi Tgl SEP terlebih dahulu !')
        return
    }

    if (!item.jnspelayanan) {
        useToaster().error('Harap pilih Jenis Pelayanan terlebih dahulu !')
        return
    }
    
    if (!item.jnspengajuan) {
        useToaster().error('Harap pilih Jenis Pengajuan terlebih dahulu !')
        return
    }

    if (!item.keterangan) {
        useToaster().error('Harap isi Keterangan terlebih dahulu !')
        return
    }

    let json = {
        "url": `Sep/aprovalSEP`,
        "method": "POST",
        "data": {
           "request": {
              "t_sep": {
                 "noKartu": item.nokartu,
                 "tglSep": H.formatDate(item.tglsep,'YYYY-MM-DD'),
                 "jnsPelayanan": item.jnspelayanan,
                 "jnsPengajuan": item.jnspengajuan,
                 "keterangan": item.keterangan,
                 "user": H.namaPegawai()
              }
           }
        }
    }

    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
            isLoadingCall.value = false
            item.jsonApprovalPengajuanSEP = response
        })
}
const listPengajuanSEP = async () => {
    if (!item.bulan) {
        useToaster().error('Harap pilih bulan terlebih dahulu !')
        return
    }

    if (!item.tahun) {
        useToaster().error('Harap piih tahun terlebih dahulu !')
        return
    }

    let json = {
        "url": `Sep/persetujuanSEP/list/bulan/${item.bulan.id}/tahun/${item.tahun.id}`,
        "method": "GET",
        "data": null
    }

    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
            isLoadingCall.value = false
            if(response.metaData.code == 404) useToaster().error(response.metaData.message)
            dataSource.value = response.response.list
        }).catch((err) => {
            isLoadingCall.value = false
            console.log(err)
        })
}
const SupJR = async () => {
    if (!item.nokartu) {
        useToaster().error('Harap isi nomor kartu terlebih dahulu !')
        return
    }

    if (!item.tglsep) {
        useToaster().error('Harap isi tanggal pelayanan/sep terlebih dahulu !')
        return
    }

    let json = {
        "url": `sep/JasaRaharja/Suplesi/${item.nokartu}/tglPelayanan/${H.formatDate(item.tglsep,'YYYY-MM-DD')}`,
        "method": "GET",
        "data": null
    }

    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
            isLoadingCall.value = false
            item.jsonSupJR = response
        }).catch((err) => {
            isLoadingCall.value = false
            console.log(err)
        })
}
const DataIndukKec = async () => {
    if (!item.nokartu) {
        useToaster().error('Harap isi nomor kartu terlebih dahulu !')
        return
    }

    let json = {
        "url": `sep/KllInduk/List/${item.nokartu}`,
        "method": "GET",
        "data": null
    }

    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
            isLoadingCall.value = false
            item.jsonDataIndukKec = response
        }).catch((err) => {
            isLoadingCall.value = false
            console.log(err)
        })
}
const UpdateTglpulangSEP = async () => {
    if (!item.nosep) {
        useToaster().error('Harap isi No SEP terlebih dahulu !')
        return
    }
    if (!item.statuspulang) {
        useToaster().error('Harap isi Status Pulang terlebih dahulu !')
        return
    }
    if (!item.tglpulang) {
        useToaster().error('Harap isi Tgl Pulang terlebih dahulu !')
        return
    }

    let json = {
        "request":{
            "t_sep":{
                "noSep": item.nosep,
                "statusPulang": item.statuspulang.id,
                "noSuratMeninggal": item.nosuratmeninggal == undefined ? "" : item.nosuratmeninggal,
                "tglMeninggal": item.tglmeninggal == undefined ? "" : H.formatDate(item.tglmeninggal,'YYYY-MM-DD'),
                "tglPulang": H.formatDate(item.tglpulang,'YYYY-MM-DD'),
                "noLPManual":  item.nolpmanual == undefined ? "" : item.nolpmanual,
                "user": H.namaPegawai()
            }
        }
    }

    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
            isLoadingCall.value = false
            item.jsonUpdateTglpulangSEP = response
        }).catch((err) => {
            isLoadingCall.value = false
            console.log(err)
        })
}
const listTglPulangSEP = async () => {
    if (!item.bulan) {
        useToaster().error('Harap pilih bulan terlebih dahulu !')
        return
    }

    if (!item.tahun) {
        useToaster().error('Harap piih tahun terlebih dahulu !')
        return
    }

    let json = {
        "url": `Sep/updtglplg/list/bulan/${item.bulan.id}/tahun/${item.tahun.id}/`,
        "method": "GET",
        "data": null
    }

    isLoadingCall.value = true
    await useApi()
        .post(`/bridging/bpjs/tools`, json)
        .then((response: any) => {
            isLoadingCall.value = false
            item.jsonlistTglPulangSEP = response
        }).catch((err) => {
            isLoadingCall.value = false
            console.log(err)
        })
}
</script>
<style lang="scss">
.button {
    border-width: 0px !important;
}

.s-card {
    padding: 5px !important;
    border-radius: 0px !important;
    cursor: pointer;
    margin-top: 5px;
}

.card-inner {
    padding: 12px !important;
    border-top: none;
    border-left: 1px solid var(--fade-grey-dark-3);
    border-right: 1px solid var(--fade-grey-dark-3);
    border-bottom: 1px solid var(--fade-grey-dark-3);
}
pre.shiki {
    background: #f5f5f5!important;
    height:500px;
    overflow-y: auto;
}

.is-dark pre.shiki {
    background: #1a1a1f!important
}
</style>