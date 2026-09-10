<template>
    <ConfirmDialog />
    <VCard>
        <TabView class="tabview-custom " @tab-click="klikTab($event)">
            <TabPanel>
                <template #header>
                    <i class="fas fa-file-invoice-dollar mr-2" aria-hidden="true"></i>
                    <span>Sistem Harga Netto</span>
                </template>

                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                <div class="columns is-multiline" v-else>
                    <div class="column is-3" v-for="(data, key) in dataSistemHarga" :key="key">
                        <VCardAction avatar="/images/avatars/label/list.png" :title="data.sistemharganetto">
                        </VCardAction>
                    </div>
                </div>
            </TabPanel>
            <TabPanel>
                <template #header>
                    <i class="fas fa-comments-dollar mr-2" aria-hidden="true"></i>
                    <span>Jenis Transaksi</span>
                </template>
                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                <div v-else>
                    <div class="column">
                        <span style="font-weight: 500;">Barang Persediaan Obat Alkes</span>
                        <div class="column">
                            <span style="font-weight: 500;">Metode Ambil Harga Netto</span>
                            <div class="columns is-multiline pt-5 pl-3">
                                <div class="column is-3 p-0" v-for="items in d_SistemHarga" :key="items.id">
                                    <VRadio class="p-0 mb-2" v-model="item.mtaHargaNetto" :value="items.id"
                                        :label="items.sistemharganetto" color="info" />
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <span style="font-weight: 500;">Metode Harga Netto</span>
                            <div class="columns is-multiline pt-5 pl-3">
                                <div class="column is-3 p-0" v-for="items in d_SistemHarga" :key="items.id">
                                    <VRadio class="p-0 mb-2" v-model="item.mtdHargaNetto" :value="items.id"
                                        :label="items.sistemharganetto" color="primary" />
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <span style="font-weight: 500;">Metode Stok Harga Netto</span>
                            <div class="columns is-multiline pt-5 pl-3">
                                <div class="column is-3 p-0" v-for="items in d_SistemHarga" :key="items.id">
                                    <VRadio class="p-0 mb-2" v-model="item.mskHargaNetto" :value="items.id"
                                        :label="items.sistemharganetto" color="success" />
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <span style="font-weight: 500;">Sistem Harga Netto</span>
                            <div class="columns is-multiline pt-5 pl-3">
                                <div class="column is-3 p-0" v-for="items in d_SistemHarga" :key="items.id">
                                    <VRadio class="p-0 mb-2" v-model="item.stmHargaNetto" :value="items.id"
                                        :label="items.sistemharganetto" color="danger" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12" style="text-align:right">
                        <VButton color="primary" raised rounded icon="feather:save" :loading="isLoading"
                            @click="saveJenisTransaksi">
                            Update
                        </VButton>
                    </div>
                </div>

            </TabPanel>
            <TabPanel>
                <template #header>
                    <i class="fas fa-percent mr-2" aria-hidden="true"></i>
                    <span>Persen Harga Jual Produk</span>
                </template>

                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                <div class="columns is-multiline" v-else>
                    <div class="column is-8">
                        <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
                            <DataTable :value="dataSourcefiltered" class="p-datatable-sm" :loading="isLoading"
                                :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                                <Column field="no" header="#"></Column>
                                <Column field="persenuphargasatuan" header="Persen Harga" :sortable="true"></Column>
                                <Column field="namakelas" header="Kelas" :sortable="true"></Column>
                                <Column field="kelompokpasien" header="Kelompok Pasien" :sortable="true"></Column>
                                <Column field="rangemax" header="Range Max" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.rangemax), 2), '')
                                        }}
                                    </template>
                                </Column>
                                <Column field="rangemin" header="Range Min" style="text-align:right">
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.rangemin), 2), '')
                                        }}
                                    </template>
                                </Column>
                                <Column :exportable="false" header="Action" style="text-align:center;">
                                    <template #body="slotProps">
                                        <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle
                                            outlined raised v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                                        </VIconButton>
                                        <VIconButton type="button" icon="fas fa-trash" class="mr-3" color="danger"
                                            circle outlined raised v-tooltip.top="'Hapus'"
                                            @click="dialogConfirm(slotProps.data)">
                                        </VIconButton>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </div>
                    <div class="column is-4">
                        <VCard style="border-left:2px solid rgb(57, 128, 151)">
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <h3 v-if="item.id" class="title is-6 mb-2 mr-1">
                                        <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                                        Edit Data
                                    </h3>
                                    <h3 v-else class="title is-6 mb-2 mr-1">
                                        <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                                        Tambah Data
                                    </h3>
                                </div>
                            </div>

                            <div class="column is-12">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel>Kelompok Pasien</VLabel>
                                    <VControl icon="feather:search" class="prime-auto">
                                        <Dropdown v-model="item.kelompokPasien" :options="d_KelompokPasien"
                                            :optionLabel="'kelompokpasien'" placeholder="Pilih Kelompok Pasien"
                                            :optionValue="'id'" style="width: 100%;" :filter="true" appendTo="body"
                                            showClear />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel class="required-field">Kelas</VLabel>
                                    <VControl icon="feather:search" class="prime-auto">
                                        <Dropdown v-model="item.kelas" :options="d_Kelas" :optionLabel="'kelas'"
                                            placeholder="Pilih Kelas" :optionValue="'id'" style="width: 100%;"
                                            :filter="true" appendTo="body" showClear />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel class="required-field">Range Harga</VLabel>
                                    <VControl icon="feather:search" class="prime-auto">
                                        <Dropdown v-model="item.range" :options="d_RangeHarga" :optionLabel="'label'"
                                            placeholder="Pilih Range" :optionValue="'id'" style="width: 100%;"
                                            :filter="true" appendTo="body" showClear />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column">
                                <VField>
                                    <VLabel class="required-field">Persen Harga Satuan</VLabel>
                                    <VControl>
                                        <input v-model="item.persenHarga" class="input" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="columns is-multiline pt-5 pl-3 pr-3">
                                <div class="column is-6 pt-0">
                                    <VField>
                                        <VLabel class="required-field">Tanggal Berlaku</VLabel>
                                        <VDatePicker v-model="item.tglBerlaku" mode="dateTime" style="width: 100%"
                                            trim-weeks>
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
                                <div class="column is-6 pt-0">
                                    <VField>
                                        <VLabel class="required-field">Tanggal Berakhir</VLabel>
                                        <VDatePicker v-model="item.tglBerakhir" mode="dateTime" style="width: 100%"
                                            trim-weeks>
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
                            </div>

                            <div v-if="item.id" class="column is-12">
                                <VButton @click="save()" :loading="isLoadingBtn" type="button" icon="feather:edit"
                                    class="is-fullwidth mr-3" color="info" raised>
                                    Update Data
                                </VButton>
                                <VButton @click="clear()" type="button" icon="feather:x-circle"
                                    class="is-fullwidth is-outlined is-warning mt-3" raised>
                                    Batal Edit
                                </VButton>
                            </div>
                            <div v-else class="column is-12">
                                <VButton @click="save()" :loading="isLoadingBtn" type="button" icon="feather:save"
                                    class="is-fullwidth mr-3" color="success" raised>
                                    Simpan Data
                                </VButton>
                            </div>

                        </VCard>
                    </div>
                </div>
            </TabPanel>
            <TabPanel>
                <template #header>
                    <i class="fas fa-clipboard-list mr-2" aria-hidden="true"></i>
                    <span>Range</span>
                </template>
                <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
                <div v-else>
                    <MasterRange></MasterRange>
                </div>

            </TabPanel>
        </TabView>
    </VCard>

</template>
  
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed } from 'vue'
import DataTable from 'primevue/datatable'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Column from 'primevue/column'
import RadioButton from 'primevue/radiobutton';
import ConfirmDialog from 'primevue/confirmdialog'
import InputSwitch from 'primevue/inputswitch';
import Dropdown from 'primevue/dropdown';
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import MasterRange from './master-range.vue'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'

useHead({
    title: 'Persen Harga Jual Produk - Transmedic',
})

let dataSource: any = ref([])
const item: any = ref({
    aktif: true,
})
const confirm = useConfirm()

const d_jenisKP = ref([])
const modalDetail = ref(false)
const selectedItem = ref()

const d_View = [
    {
        name: 'Grid View',
        value: 'grid',
        icon: 'fas fa-id-card-alt',
    },
    {
        name: 'List View',
        value: 'list',
        icon: 'fas fa-list',
    },
]
const selectView: any = ref()
selectView.value = 'list'

const d_Kelas: any = ref([])
const d_KelompokPasien: any = ref([])
const d_SistemHarga: any = ref([])
const d_RangeHarga: any = ref([])
const dataSistemHarga: any = ref([])
let loadData: any = ref(true)
let isLoadingBtn: any = ref(false)
let isLoading: any = ref(false)

const filters = ref('')

const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.kelompokpasien.match(new RegExp(filters.value, 'i')),
            items.namakelas.match(new RegExp(filters.value, 'i'))
        )
    })
})

const fetchData = async () => {

    isLoading.value = true
    await useApi().get(`/sysadmin/get-data-persen-harga-jual?statusenabled=${item.value.aktif}`).then((response)=>{
         response.forEach((elements: any, i: any) => {
            elements.no = i + 1
        })
        dataSource.value = response

    })
    isLoading.value = false
}

const fetchDataJenisTrans = async () => {
    let response = await useApi().get(`/sysadmin/get-data-jenis-transaksi`)
    item.value.jenistransaksifk = response.id
    item.value.mtaHargaNetto = response.metodeambilharganetto
    item.value.mtdHargaNetto = response.metodeharganetto
    item.value.mskHargaNetto = response.metodestokharganetto
    item.value.stmHargaNetto = response.sistemharganetto
    loadData.value = false
}

const fetchDataSistemHarga = async () => {
    let response = await useApi().get(`/sysadmin/get-data-sistem-harga`)
        dataSistemHarga.value = response
}

const getKelompokPasien = async () => {

    let response = await useApi().get('sysadmin/get-combo-persen-harga-jual')
    d_SistemHarga.value = response.sistemharganetto
    d_KelompokPasien.value = response['kelompokPasien'].map((e: any) => { return { kelompokpasien: e.kelompokpasien, id: e.id, default: e } })
    d_Kelas.value = response['kelas'].map((e: any) => { return { kelas: e.namakelas, id: e.id, default: e } })
    d_RangeHarga.value = response['rangeHarga'].map((e: any) => { return { label: e.nilai, id: e.id, default: e } })

}


const save = async () => {

    // if (!item.value.kelompokPasien) {
    //     useToaster().error('Kelompok Pasien harus di isi')
    //     return
    // }
    if (!item.value.kelas) {
        useToaster().error('Kelas harus di isi')
        return
    }
    if (!item.value.range) {
        useToaster().error('Range Harga harus di isi')
        return
    }
    if (!item.value.persenHarga) {
        useToaster().error('Persen Harga harus di isi')
        return
    }
    if (!item.value.tglBerlaku) {
        useToaster().error('Tanggal Berlaku harus di isi')
        return
    }
    if (!item.value.tglBerakhir) {
        useToaster().error('Tangal Berakhir harus di isi')
        return
    }
    var objSave =
    {
        'id': item.value.id ? item.value.id : '',
        'kelompokPasienfk': item.value.kelompokPasien ? item.value.kelompokPasien : '',
        'statusenabled': item.value.statusenabled,
        'kelasfk': item.value.kelas,
        'rangefk': item.value.range,
        'persenHarga': item.value.persenHarga,
        'tglBerlaku': H.formatDate(item.value.tglBerlaku, 'YYYY-MM-DD HH:mm:ss'),
        'tglBerakhir': H.formatDate(item.value.tglBerakhir, 'YYYY-MM-DD HH:mm:ss'),

    }
    isLoadingBtn.value = true
    await useApi().post(`/sysadmin/simpan-data-persen-harga-jual`, objSave).then((response: any) => {
        isLoadingBtn.value = false
        fetchData()
    }, (error) => {
        isLoadingBtn.value = false
    })

    clear()

}

const saveJenisTransaksi = async () => {

    var objSave =
    {
        'id': item.value.jenistransaksifk,
        'mtdAmbilHargaNetto': item.value.mtaHargaNetto,
        'mtdHargaNetto': item.value.mtdHargaNetto,
        'mtdStokHargaNetto': item.value.mskHargaNetto,
        'sistemHargaNetto': item.value.stmHargaNetto,
    }
    isLoading.value = true
    await useApi().post(`/sysadmin/simpan-jenis-transaksi`, objSave).then((response: any) => {
        isLoading.value = false
        fetchDataJenisTrans()
    }, (error) => {
        isLoading.value = false
    })

}

async function deleteItem(e: any) {
    isLoading.value = true
    await useApi().post(
        `sysadmin/delete-data-persen-harga-jual`, { 'id': e.id }).then((response: any) => {
            clear()
            fetchData()
            isLoading.value = false
        }, (error) => {
        })
}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleteItem(e)

        },
        reject: () => { },
    })
}

const changeView = (e: any) => {
    selectView.value = e
}

const edit = (e: any) => {
    item.value.id = e.id
    item.value.kelompokPasien = e.kelompokpasienfk
    item.value.kelas = e.kelasfk
    item.value.range = e.range
    item.value.persenHarga = e.persenuphargasatuan
    item.value.tglBerlaku = e.tglberlakuawal
    item.value.tglBerakhir = e.tglberlakuakhir
}
const detail = (e: any) => {
    item.value.id = e.id
    item.value.kelompokpasien = e.kelompokpasienfk
    item.value.kelas = e.kodeexternal
    item.value.range = e.status
    item.value.persenHarga = e.jeniskondisipasien
    item.value.tglBerlaku = e.jeniskondisipasien
    item.value.tglBerakhir = e.jeniskondisipasien
    modalDetail.value = true
}

const clear = () => {

    delete item.value.id
    delete item.value.kelompokPasien
    delete item.value.kelas
    delete item.value.range
    delete item.value.persenHarga
    delete item.value.tglBerlaku
    delete item.value.tglBerakhir

}

fetchDataSistemHarga()
fetchData()
getKelompokPasien()
fetchDataJenisTrans()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

.fs-075 {
    font-size: 0.9rem;
}

.is-navbar {
    .form-layout {
        margin-top: 30px;
    }
}

.mix {
    margin-bottom: -1.7rem;
    max-width: 83%;
    margin-left: 3rem;
    margin-top: -5.5rem;
    opacity: .9;
}

#custom {
    .tabs-inner {
        margin-left: -21px;
        padding-left: 20px;
        padding-top: 18px;
        margin-top: -21px;
        border-top-left-radius: 11px;
        border-left: solid hsl(19deg 100% 75% / 72%) 3px;
    }
}

.tabs-wrapper.is-triple-slider.is-squared {
    display: none;
}

.form-layout {
    // max-width: 540px;
    margin: 0 auto;

    &.is-separate {
        // max-width: 1040px;

        .form-outer {
            background: none;
            border: none;

            .form-body {
                display: flex;

                .form-section {
                    flex-grow: 2;
                    padding: 10px;
                    width: 50%;

                    .form-section-inner {
                        @include vuero-s-card;

                        padding: 40px;

                        &.has-padding-bottom {
                            padding-bottom: 60px;
                            height: 100%;
                        }

                        >h3 {
                            font-family: var(--font-alt);
                            font-size: 1.2rem;
                            font-weight: 600;
                            color: var(--dark-text);
                            margin-bottom: 30px;
                        }

                        .columns {
                            .column {
                                padding-top: 0.25rem;
                                padding-bottom: 0.25rem;
                            }
                        }

                        .radio-boxes {
                            display: flex;
                            justify-content: space-between;
                            margin-left: -8px;
                            margin-right: -8px;

                            .radio-box {
                                position: relative;
                                width: calc(50% - 16px);
                                margin: 8px;

                                &:focus-within {
                                    border-radius: 3px;
                                    outline-offset: var(--accessibility-focus-outline-offset);
                                    outline-width: var(--accessibility-focus-outline-width);
                                    outline-style: var(--accessibility-focus-outline-style);
                                    outline-color: var(--primary);
                                }

                                input {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    height: 100%;
                                    width: 100%;
                                    opacity: 0;
                                    cursor: pointer;

                                    &:checked {
                                        +.radio-box-inner {
                                            background: var(--primary);
                                            border-color: var(--primary);
                                            box-shadow: var(--primary-box-shadow);

                                            .fee,
                                            p {
                                                color: var(--smoke-white);
                                            }
                                        }
                                    }
                                }

                                .radio-box-inner {
                                    background: var(--white);
                                    border: 1px solid var(--fade-grey-dark-3);
                                    text-align: center;
                                    border-radius: var(--radius);
                                    font-family: var(--font);
                                    font-weight: 600;
                                    font-size: 0.9rem;
                                    transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                                        height 0.3s, width 0.3s;
                                    padding: 30px 20px;

                                    .fee {
                                        font-family: var(--font);
                                        font-weight: 700;
                                        color: var(--dark-text);
                                        font-size: 2.4rem;
                                        line-height: 1;

                                        span {
                                            &::after {
                                                content: '$';
                                                position: relative;
                                                top: -10px;
                                                font-size: 1.5rem;
                                            }
                                        }
                                    }

                                    p {
                                        font-family: var(--font-alt);
                                    }
                                }
                            }
                        }

                        .control {
                            >p {
                                padding-top: 12px;

                                >span {
                                    display: block;
                                    font-size: 0.9rem;

                                    span {
                                        font-weight: 500;
                                        color: var(--dark-text);
                                    }
                                }
                            }
                        }
                    }

                    .form-section-outer {
                        .checkboxes {
                            padding: 16px 0;

                            .checkbox {
                                padding: 0;
                                font-size: 0.9rem;
                            }
                        }

                        .button-wrap {
                            .button {
                                min-height: 60px;
                                font-size: 1.05rem;
                                font-weight: 600;
                                font-family: var(--font-alt);
                            }
                        }
                    }
                }
            }
        }
    }
}

.is-dark {
    .form-layout {
        &.is-separate {
            .form-outer {
                background: none !important;

                .form-body {
                    .form-section {
                        .form-section-inner {
                            @include vuero-card--dark;

                            >h3 {
                                color: var(--dark-dark-text);
                            }

                            .radio-boxes {
                                .radio-box {
                                    input:checked+.radio-box-inner {
                                        background: var(--primary);
                                        border-color: var(--primary);
                                        box-shadow: var(--primary-box-shadow);

                                        .fee,
                                        p {
                                            color: var(--smoke-white);
                                        }
                                    }

                                    .radio-box-inner {
                                        background: var(--dark-sidebar-light-2);
                                        border-color: var(--dark-sidebar-light-12);

                                        .fee {
                                            color: var(--dark-dark-text);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (max-width: 767px) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;
                    flex-direction: column;

                    .form-section {
                        width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;

                    // flex-direction: column;

                    .form-section {
                        // width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

.all-projects {
    .all-projects-header {
        display: flex;
        padding: 20px;
        background: var(--white);
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-large);
        margin-bottom: 1.5rem;

        .header-item {
            width: 25%;
            border-right: 1px solid var(--fade-grey-dark-3);

            &:last-child {
                border-right: none;
            }

            .item-inner {
                text-align: center;

                .lnil,
                .lnir {
                    font-size: 2.2rem;
                    margin-bottom: 6px;
                    color: var(--primary);
                }

                span {
                    display: block;
                    font-family: var(--font);
                    font-weight: 600;
                    font-size: 1.4rem;
                    color: var(--dark-text);
                }

                p {
                    font-family: var(--font-alt);
                }
            }
        }
    }

    .projects-card-grid {
        .grid-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 220px;
            padding: 20px;
            background: var(--white);
            border: 1px solid var(--fade-grey-dark-3);
            border-radius: var(--radius-large);

            .top-section {
                .head {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 8px;

                    h3 {
                        font-size: 1rem;
                        font-family: var(--font-alt);
                        color: var(--dark-text);
                        font-weight: 600;
                    }
                }

                .body {
                    p {
                        font-family: var(--font);
                        color: var(--light-text);
                    }
                }
            }

            .bottom-section {
                display: flex;

                .foot-block {
                    margin-right: 30px;

                    .heading {
                        font-family: var(--font-alt);
                        font-size: 0.75rem;
                        color: var(--light-text-dark-22);
                    }

                    >p {
                        padding-top: 5px;
                    }

                    .developers {
                        display: flex;

                        .v-avatar {
                            margin-right: 6px;
                        }
                    }
                }
            }
        }
    }
}

.heading {
    font-family: var(--font-alt);
    font-size: 0.75rem;
    color: var(--light-text-dark-22);
}

.is-dark {
    .all-projects {
        .all-projects-header {
            background: var(--dark-sidebar-light-6);
            border-color: var(--dark-sidebar-light-12);

            .header-item {
                border-color: var(--dark-sidebar-light-18);

                span {
                    color: var(--dark-dark-text);
                }

                i {
                    color: var(--primary) !important;
                }
            }
        }

        .projects-card-grid {
            .grid-item {
                background: var(--dark-sidebar-light-6);
                border-color: var(--dark-sidebar-light-12);

                .top-section {
                    .head {
                        h3 {
                            color: var(--dark-dark-text);
                        }
                    }
                }

                .bottom-section {
                    .foot-block {
                        .heading {
                            color: var(--light-text-dark-12);
                        }
                    }
                }
            }
        }
    }
}
</style>