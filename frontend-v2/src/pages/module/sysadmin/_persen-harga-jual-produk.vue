<template>
    <section>

        <div class="columns is-multiline">
            <div class="column is-7">
                <VCard></VCard>
            </div>
            <div class="column">
                <VCard>
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Kelompok Pasien</VLabel>
                                <VControl icon="feather:search" class="prime-auto">
                                    <Dropdown v-model="item.kelompokPasien" :options="d_KelompokPasien"
                                        :optionLabel="'kelompokpasien'" placeholder="Pilih Kelompok Pasien"
                                        :optionValue="'default'" style="width: 100%;" :filter="true" appendTo="body"
                                        showClear :disabled="sourceDataSementara.length > 0 ? true : false" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Kelas</VLabel>
                                <VControl icon="feather:search" class="prime-auto">
                                    <Dropdown v-model="item.kelas" :options="d_Kelas" :optionLabel="'kelas'"
                                        placeholder="Pilih Kelas" :optionValue="'default'" style="width: 100%;"
                                        :filter="true" appendTo="body" showClear @change="selectedItem(item.kelas)" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Range Harga</VLabel>
                                <VControl icon="feather:search" class="prime-auto">
                                    <Dropdown v-model="item.range" :options="d_RangeHarga" :optionLabel="'label'"
                                        placeholder="Pilih Range Harga" :optionValue="'default'" style="width: 100%;"
                                        :filter="true" appendTo="body" showClear />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column">
                            <VField>
                                <VLabel>Persen Harga Satuan</VLabel>
                                <VControl>
                                    <input v-model="item.persenHarga" class="input" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-1 mt-5 pt-4">
                            <VIconButton color="info" icon="fas fa-plus" @click="tambah" :loading="loadSearch" outlined />
                        </div>
                    </div>

                    <div class="column is-12 pt-0">
                        <div class="content">
                            <div class="is-divider" data-content="Persen Harga Jual" />
                        </div>
                    </div>

                    <DataTable :value="sourceDataSementara" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                        class="p-datatable-sm" tableStyle="min-width: 10rem"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                        showGridlines>
                        <Column field="no" header="No" style="min-width: 5px;" />
                        <Column field="kelompokPasien" header="Kelompok pasien" style="min-width: 10px;" />
                        <Column field="kelas" header="Kelas" style="min-width: 10px;" />
                        <Column field="range" header="Range" style="min-width: 10px;" />
                        <Column field="persenHarga" header="Persen" style="min-width: 10px;" />
                        <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
                            <template #body="slotProps">
                                <!-- <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined
                                    raised :loading="isLoadingBtn" v-tooltip.top="'Edit'"
                                    @click="editDataSupplier(slotProps.data)">
                                </VIconButton> -->
                                <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                                    v-tooltip.top="'Hapus'" @click="hapus(slotProps.data)">
                                </VIconButton>
                            </template>
                        </Column>
                    </DataTable>

                    <VButton @click="saveData()" type="button" icon="feather:save"
                        class="is-fullwidth mr-3" color="success" raised>
                        Simpan Data
                    </VButton>

                </VCard>
            </div>
        </div>

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
import OverlayPanel from 'primevue/overlaypanel';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { any } from 'zod';

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Daftar Remunerasi Pegawai - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    tglPelayanan: new Date(),
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const NOCLOSING = useRoute().query.noclosing as string
const IDDOKTER = useRoute().query.iddokter as string
const JENIS = useRoute().query.klmpenghasil as string
const PEGAWAI = useRoute().query.namapegawai as string

const router = useRouter()
const confirm = useConfirm()
const activeTab = ref(0);
const dataSource: any = ref([])
const SourceRemun: any = ref([])
const d_Kelas: any = ref([])
const d_KelompokPasien: any = ref([])
const d_RangeHarga: any = ref([])
const op = ref();
const selected: any = ref({})
const isSelected: any = ref(false)
const sourceDataSementara: any = ref([])
const expandedRows = ref();
let d_Pegawai: any = ref([])
let d_Ruangan: any = ref([])
let loadSearch: any = ref(false)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {

    item.value.TtarifLayanan = 0
    item.value.TjasaRemun = 0

    await useApi().get(`remunerasi/get-detail-remun-pegawai?IdDokter=${IDDOKTER}&noclosing=${NOCLOSING}&klmpenghasil=${JENIS}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            item.value.TtarifLayanan = parseFloat(element.hargasatuan) + item.value.TtarifLayanan
            item.value.TjasaRemun = parseFloat(element.jenispagunilai) + item.value.TjasaRemun
        });

        item.value.FtarifLayanan = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.TtarifLayanan, 2), '')
        item.value.FtjRemun = 'Rp. ' + H.formatRupiah(H.roundToDecimal(item.value.TjasaRemun, 2), '')
        dataSource.value = response
    })
}

const saveData = async ()=>{

    await useApi().post('sysadmin/simpan-data-persen-harga-jual',{'detail' : sourceDataSementara.value}).then((response)=>{

    })
}

const getKelompokPasien = async () => {

    await useApi().get('sysadmin/get-combo-persen-harga-jual').then((response) => {
        d_KelompokPasien.value = response['kelompokPasien'].map((e: any) => { return { kelompokpasien: e.kelompokpasien, id: e.id, default: e } })
        d_Kelas.value = response['kelas'].map((e: any) => { return { kelas: e.namakelas, id: e.id, default: e } })
        d_RangeHarga.value = response['rangeHarga'].map((e: any) => { return { label: e.nilai, id: e.id, default: e } })
    })
}

const selectedItem = (e: any) => {
    if (e == null) {
        isSelected.value = false
    } else {
        isSelected.value = true
    }
}

const tambah = () => {
    let status = true
    if (!item.value.kelompokPasien) {
        H.alert('error', 'Kelompok Pasien Tidak Boleh Kosong')
        return
    }
    if (!item.value.kelas) {
        H.alert('error', 'Kelas Tidak Boleh Kosong')
        return
    }
    if (!item.value.range) {
        H.alert('error', 'Range Harga Tidak Boleh Kosong')
        return
    }
    if (!item.value.persenHarga) {
        H.alert('error', 'Persen Harga Tidak Boleh Kosong')
        return
    }

    sourceDataSementara.value.forEach((element: any) => {
        if (element.kelasfk == item.value.kelas.id && element.rangefk == item.value.range.id) {
            status = false
            H.alert('error', 'Data Tersebut Telah Tersedia')
            return
        }
        return
    });
    if (status == true) {
        let datas = {
            no: sourceDataSementara.value.length == 0 ? 1 : sourceDataSementara.value.length + 1,
            persenHarga: item.value.persenHarga,
            kelas: item.value.kelas.namakelas,
            kelasfk: item.value.kelas.id,
            kelompokPasienfk: item.value.kelompokPasien.id,
            kelompokPasien: item.value.kelompokPasien.kelompokpasien,
            range: item.value.range.nilai,
            rangefk: item.value.range.id,
        }
        sourceDataSementara.value.push(datas)
    }
    clear()
}


const hapus = (e: any) => {
    sourceDataSementara.value.forEach((element: any, i: any) => {
        if (element.no == e.no) {
            sourceDataSementara.value.splice(i, 1)
        }
    });
}

const clear = () => {
    delete item.value.range
    delete item.value.persenHarga
}

getKelompokPasien()


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
}
</style>
