
<template>
    <div class="column">
        <VCard>
            <div class="column is-12">
                <div class="search-widget">
                    <div class="field">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <h3 class="title is-5 mb-2 mr-1">Penerimaan Kasir </h3>
                            </div>
                            <div class="column is-4 pt-0 pb-0">
                                <span>Periode</span>
                                <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks class="pt-2">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" class="input-calendar"
                                                    v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" class="input-calendar"
                                                    v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column pt-0 pb-0 is-3">
                                <span>Nama Kasir</span>
                                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="item.filterdokterfk" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Kasir" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2 pt-0 pb-0">
                                <span>Cara Bayar</span>
                                <VField class="is-autocomplete-select pt-2">
                                    <VControl icon="feather:search">
                                        <Multiselect mode="single" v-model="item.carabayar" :options="d_caraBayar"
                                            placeholder="Pilih data" :searchable="true" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2 pt-0 pb-0">
                                <span>Setor</span>
                                <VField class="is-autocomplete-select pt-2">
                                    <VControl icon="feather:search">
                                        <Multiselect mode="single" v-model="item.KetSetor" :options="d_Setor"
                                            placeholder="Pilih data" :searchable="true" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column mt-4" style="margin-left: auto:  !important;">
                                <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                                    @click="cari()" :loading="isLoadingBtn">
                                </VIconButton>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </VCard>
    </div>

    <div class="column">
        <VCard>
            <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                <TabPanel>
                    <template #header>
                        <i class="fas fa-users mr-2" aria-hidden="true"></i>
                        <span>Setoran Harian</span>
                        <Badge :value="dataSource.length" v-if="dataSource.length > 0" severity="danger" class="ml-2" />
                    </template>

                    <div class="columns is-multiline">
                        <div class="column is-4" v-for="(cb, i) in d_CaraBayarFoot" style="margin-top:10px">
                            <VCardCustom :style="'padding:5px 25px;margin:0;background:#fafafa'">
                                <div :class="'label-status ' + listColor[i]">
                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                    <span class="ml-1">{{ cb.carabayar }}</span>
                                </div>
                                <small class="text-bold-custom">{{
                                    H.formatRp(cb.total,
                                        '')
                                }}</small>
                            </VCardCustom>
                        </div>
                        <div class="column is-4" style="margin-top:10px">
                            <VCardCustom :style="'padding:5px 25px;margin:0;background:#fafafa'">
                                <div :class="'label-status ' + listColor">
                                    <i aria-hidden="true" class="fas fa-circle"></i>
                                    <span class="ml-1">Total Penerimaan</span>
                                </div>
                                <small class="text-bold-custom">{{
                                    H.formatRp(item.totalAll,
                                        '')
                                }}</small>
                            </VCardCustom>
                        </div>
                        <div class="column is-8">
                            <div class="column is-12">
                                <VButton color="success" icon="fas fa-print" raised rounded style="margin-left:20px;"
                                    @click="cetakLaporanPenerimaanHarian()"> Laporan Penerimaan Harian
                                </VButton>

                                <VButton color="warning" icon="fas fa-print" style="margin-left:20px;" raised rounded  @click="cetakLaporan()">
                                    Laporan Penerimaan Per Transaksi
                                </VButton>
                            </div>
                        </div>
                    </div>

                    <DataTable :value="dataSource" tableStyle="min-width: 50rem" scrollable :paginator="true" :rows="10"
                        :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" showGridlines>
                        <template #header>
                            <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                <span class="text-xl text-900 font-bold">Rincian Transaksi</span>
                                <VButton color="danger" icon="feather:x" raised rounded style="margin-left:20px;"
                                    @click="batalSetor()"> Batal Setor
                                </VButton>
                            </div>
                        </template>
                        <Column field="no" header="No" frozen></Column>
                        <Column field="noSbm" header="No SBM" style="min-width: 150px" frozen></Column>
                        <Column field="statussetor" header="Status" style="min-width: 100px;">
                            <template #body="slotProps">
                                <Tag :value="slotProps.data.statussetor" :severity="getLabel(slotProps.data.statussetor)" />
                            </template>
                        </Column>
                        <Column field="namaruangan" header="Nama Ruangan" style="min-width: 150px"></Column>
                        <Column field="tglSbm" header="Tanggal" style="min-width: 150px"></Column>
                        <Column field="namapasien" header="Nama Pasien" style="min-width: 150px"></Column>
                        <Column field="namapasien_klien" header="Deskripsi" style="min-width: 100px"></Column>
                        <Column field="keterangan" header="Keterangan" style="min-width: 150px"></Column>
                        <Column field="caraBayar" header="Cara Bayar" style="min-width: 100px"></Column>
                        <Column field="totalPenerimaan" header="Total Penerimaan" style="min-width: 150px">
                            <template #body="slotProps">
                                {{ H.formatRp(slotProps.data.totalPenerimaan, 'Rp. ') }}
                            </template>
                        </Column>
                        <Column field="namaPenerima" header="Kasir" style="min-width: 200px"></Column>



                        <template #footer> Total Transaksi = {{ dataSource ? dataSource.length : 0 }} </template>
                    </DataTable>


                </TabPanel>
                <TabPanel>
                    <template #header>
                        <i class="fas fa-check-circle mr-2" aria-hidden="true"></i>
                        <span>Detail Penerimaan</span>
                        <!-- <Badge :value="dataResult.length" v-if="dataResult.length > 0" severity="danger" class="ml-2" /> -->
                    </template>

                    <DataTable :value="dataCaraBayar" tableStyle="min-width: 50rem" scrollable :paginator="true" :rows="10"
                        :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" showGridlines>
                        <template #header>
                            <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                <span class="text-xl text-900 font-bold">Detail Penerimaan</span>

                            </div>
                        </template>
                        <Column field="caraBayar" header="Cara Bayar" style="min-width: 150px"></Column>
                        <Column field="totalPenerimaan" header="Total Penerimaan" style="min-width: 150px">
                            <template #body="slotProps">
                                {{ H.formatRp(slotProps.data.totalPenerimaan, 'Rp. ') }}
                            </template>
                        </Column>
                        <Column field="totalSetor" header="Total Setor" style="min-width: 150px">
                            <template #body="slotProps">
                                {{ H.formatRp(slotProps.data.totalSetor, 'Rp. ') }}
                            </template>
                        </Column>
                        <Column field="sisa" header="Sisa" style="min-width: 150px">
                            <template #body="slotProps">
                                {{ H.formatRp(slotProps.data.sisa, 'Rp. ') }}
                            </template>
                        </Column>
                        <Column :exportable="false" header="##" style="text-align: center;">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="fas fa-arrow-right" class="mr-2" color="warning" circle
                                    outlined raised v-tooltip.top="'Setor'" @click="setor(slotProps.data)">
                                </VIconButton>
                            </template>
                        </Column>

                    </DataTable>


                </TabPanel>
            </TabView>
        </VCard>
    </div>
    <Dialog v-model:visible="modalInput" modal header="Setor Penerimaan" :style="{ width: '50vw' }">
        <div class="columns is-multiline">
            <div class="column is-4">
                <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tglsetor" color="green" trim-weeks mode="dateTime"
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }" class="pb-0">
                        <VField>
                            <VLabel class="required-field">Tanggal Setor</VLabel>
                            <VControl icon="feather:calendar">
                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </VField>
                    </template>
                </VDatePicker>
            </div>
            <div class="column is-4">
                <span>Cara Bayar</span>
                <VField class="is-autocomplete-select pt-2">
                    <VControl icon="feather:search">
                        <Multiselect mode="single" v-model="item.caraBayar" :options="d_caraBayar" placeholder="Pilih data"
                            :searchable="true" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <span>Cara Setor</span>
                <VField class="is-autocomplete-select pt-2">
                    <VControl icon="feather:search">
                        <Multiselect mode="single" v-model="item.caraSetor" :options="d_caraSetor" placeholder="Pilih data"
                            :searchable="true" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Sub Total Penerimaan</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.penerimaanTotal" type="text" class="input is-rounded"
                            placeholder="Lain-lain " />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Sub Total Setoran</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.setoran" type="text" class="input is-rounded" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Sisa</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.sisa2" type="text" class="input is-rounded" />
                    </VControl>
                </VField>
            </div>

        </div>
        <template #footer>
            <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
                Batal
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingBtn"
                @click="simpan()"> Simpan
            </VButton>
        </template>
    </Dialog>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog';
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import moment from 'moment'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Badge from 'primevue/badge';
import Tag from 'primevue/tag';

useHead({
    title: 'Penerimaan Kasir - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
    tglsetor: new Date(),
    isnotverif: true
})

const listColor: any = ref(Object.keys(useThemeColors()))

const activeTab = ref(0)
const router = useRouter()
const route = useRoute()
const { y } = useWindowScroll()
const d_Ruangan = ref([])
const sourceOrder = ref([])
const dataSource = ref([])
const dataCaraBayar = ref([])
const listCaraBayar = ref([])
const dataDetail2: any = ref([])

const caraBayararr1: any = ref([])
const caraBayararr: any = ref([])

const dataSource2 = ref([])
const dataSetoranKasir = ref([])
const addDataDetail = ref([])
const dataResult: any = ref([])
const d_Dokter = ref([])
const modalInput = ref(false)
const isLoadingBtn = ref(false)
const isLoadingBB = ref(false)
const isLoading = ref(false)
const d_CaraBayarFoot: any = ref([])
const d_caraBayar: any = ref([])
const d_caraSetor: any = ref([])
const d_Setor = [
  {
    label: 'Belum Setor',
    value: '1',
  },
  {
    label: 'Setor',
    value: '2',
  },
]


const currentPage: any = ref({
    limit: 5,
    rows: 50
})

currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})

const fetchData = async () => {
    isLoading.value = true;
    let dari = `?dari=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
    let sampai = `&sampai=${moment(item.value.periode.end).format('YYYY-MM-DD')}`
    let carabayar = item.value.carabayar ? `&carabayar=${item.value.carabayar}` : ''
    let KetSetor = item.value.KetSetor ? `&KetSetor=${item.value.KetSetor}` : ''
    let idPegawai = item.value.isdokter == true ? `&idPegawai=${useUserSession().getUser().pegawai.id}` : ''

    item.value.totalAll = 0
    await useApi().get(`bendahara/get-daftar-sbm${dari}${sampai}${idPegawai}${carabayar}${KetSetor}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        for (var p = response.carabayar.length - 1; p >= 0; p--) {
            const elementq = response.carabayar[p];
            if (elementq.total == 0) {
                response.carabayar.splice(p, 1)
            }
        }
        isLoading.value = false;
        d_CaraBayarFoot.value = response.carabayar
        item.value.totalAll = response.total
        dataSource.value = response.data

    })
}
const fetchDropdown = () => {
    useApi().get(
        `/bendahara/get-list-bayar`).then((response: any) => {
            d_caraBayar.value = response.carabayar.map((e: any) => { return { label: e.carabayar, value: e.id, default: e } })
            d_caraSetor.value = response.carasetor.map((e: any) => { return { label: e.carasetor, value: e.id, default: e } })

        })
}

const getLabel = (statussetor: any) => {
    switch (statussetor) {
        case 'Belum Setor':
            return 'danger';
        case 'Setor':
            return 'success';
    }
}


const fetchDetail = async () => {
    dataCaraBayar.value = []
    dataDetail2.value = []
    dataResult.value = []
    let ttlPasien = 0;
    let ttlKlaim = 0;
    let ttlSsetor = 0;
    let dataDetail = [];
    let adaDetail = 0;
    let jmlSetor = 0;

    let caraBayararr: any = ref([]);

    if (dataSource.value != undefined) {
        for (let k = 0; k < dataSource.value.length; k++) {
            const element = dataSource.value[k];
            dataResult.value.push(element)
        }

        for (let x = dataResult.value.length - 1; x >= 0; x--) {
            if (dataResult.value[x].statussetor == 'Setor') {
                dataResult.value.splice(x, 1);
            }
        }

        for (let i = 0; i < dataResult.value.length; i++) {
            ttlPasien = ttlPasien + 1;
            adaDetail = 0

            for (let x = 0; x < dataDetail2.value.length; x++) {
                if (dataDetail2.value[x].caraBayar == dataResult.value[i].caraBayar) {
                    dataDetail2.value[x].totalPenerimaan = parseFloat(dataDetail2.value[x].totalPenerimaan) + parseFloat(dataResult.value[i].totalPenerimaan)
                    adaDetail = 1;
                }
            };
            if (adaDetail == 0) {
                jmlSetor = 0;
                caraBayararr = { id: dataResult.value[i].idCaraBayar, namaLengkap: dataResult.value[i].caraBayar };
                let dataDetail0: any = ref([]);
                for (let f = 0; f < addDataDetail.value.length; f++) {
                    if (dataResult.value[i].caraBayar == addDataDetail.value[f].caraBayar) {
                        dataDetail0.value.push(addDataDetail.value[f]);
                        jmlSetor += parseFloat(addDataDetail.value[f].jumlah);
                    };
                }
                if (dataDetail0.value.length > 0) {
                    dataDetail = {
                        caraBayar: dataResult.value[i].caraBayar,
                        idCaraBayar: dataResult.value[i].idCaraBayar,
                        totalPenerimaan: dataResult.value[i].totalPenerimaan,
                        totalSetor: jmlSetor,
                        sisa: dataResult.value[i].totalPenerimaan,
                        detail: dataDetail0
                    };
                } else {
                    let detailSetorans = [];
                    for (let z = 0; z < dataResult.value[i].details.length; z++) {
                        if (dataResult.value[i].caraBayar == dataResult.value[i].details[z].carabayar) {
                            dataResult.value[i].details[z].caraBayar = dataResult.value[i].details[z].carabayar;
                            dataResult.value[i].details[z].caraSetor = dataResult.value[i].details[z].carasetor;
                            detailSetorans.push(dataResult.value[i].details[z]);
                            jmlSetor += parseFloat(dataResult.value[i].details[z].jumlah);
                        };
                    }
                    dataDetail = {
                        caraBayar: dataResult.value[i].caraBayar,
                        idCaraBayar: dataResult.value[i].idCaraBayar,
                        totalPenerimaan: dataResult.value[i].totalPenerimaan,
                        totalSetor: jmlSetor,
                        sisa: dataResult.value[i].totalPenerimaan,
                        detail: detailSetorans
                    };
                    /*end*/
                }

                dataDetail2.value.push(dataDetail);
                caraBayararr1.value.push(caraBayararr.value);
            }
        };

        for (var i = 0; i < dataDetail2.value.length; i++) {
            dataDetail2.value[i].sisa = parseFloat(dataDetail2.value[i].totalPenerimaan) - parseFloat(dataDetail2.value[i].totalSetor)
            ttlKlaim += parseInt(dataDetail2.value[i].totalPenerimaan);
            ttlSsetor += parseInt(dataDetail2.value[i].totalSetor);
        }
        dataCaraBayar.value = dataDetail2.value
        listCaraBayar.value = caraBayararr1.value
    };
    item.value.totalSubTotal = "Rp. " + parseFloat(ttlKlaim).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
    item.value.totalSetoran = "Rp. " + parseFloat(ttlSsetor).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
    item.value.sisa = "Rp. " + parseFloat(ttlKlaim - ttlSsetor).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")

    item.value.jumlah = '';

    // console.log(item.value.totalSetoran)
}

const setor = (e: any) => {

    item.value.penerimaanTotal = parseFloat(e.totalPenerimaan)
    item.value.setoran = parseFloat(e.totalPenerimaan)
    item.value.sisa2 = 0

    modalInput.value = true
}

const kembaliKeun = () => {
    modalInput.value = false
}

const simpan = async () => {

    let objData = [];
    let objStr = {};
    for (var i = 0; i < dataCaraBayar.value.length; i++) {
        // for (var j = 0; j <= dataCaraBayar.value[i].detail.length; j++) {
        objStr = {
            "kdCaraBayar": dataCaraBayar.value[i].idCaraBayar,
            "totalPenerimaan": dataCaraBayar.value[i].totalPenerimaan,
            "kdAccountBank": dataCaraBayar.value[i].idBankAccount,
            "idCaraSetor": item.value.caraSetor,

        }
        objData.push(objStr)
        // }

    }

    let objSBM = []
    for (let j = 0; j < objData.length; j++) {
        const Datas = objData[j];
        for (let x = 0; x < dataSource.value.length; x++) {
            const element = dataSource.value[x];
            if (element.idCaraBayar == Datas.kdCaraBayar) {
                objSBM.push({ 'norec_sbm': element.noRec })
            }
        }
    }

    let formData = {
        'tglsetor': item.value.tglsetor,
        'setoran': item.value.setoran,
        'kdPegawai': useUserSession().getUser().pegawai.id,
        'detailSetoran': objData,
        'detailSBM': objSBM,
    }
    isLoadingBtn.value = true
    await useApi().post('/bendahara/save-setoran-kasir', formData).then((r) => {
        isLoadingBtn.value = false
        fetchData()
        modalInput.value = false
    }).catch((e: any) => {
        isLoadingBtn.value = false
    })
}
const batalSetor = async () => {
    let total = 0
    let objSbm = []

    if (dataSource.value == undefined) {
        H.alert('error', 'Belum ada data yang di setor')
        return
    }

    for (let i = 0; i < dataSource.value.length; i++) {
        const element = dataSource.value[i];
        if (element.statussetor == 'Setor') {
            total = total + parseFloat(element.totalPenerimaan)
            objSbm.push({
                'norec_sbm': element.noRec,
                'noclosing': element.noClosing
            })
        }
    }
    if (objSbm.length == 0) {
        H.alert('error', 'Belum ada data yang di setor')
        return
    }
    total = "Rp. " + parseFloat(total).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
    let objSaveNew = {
        'details': objSbm
    }
    isLoadingBB.value = true
    await useApi().post('/bendahara/batal-setoran', objSaveNew).then((r) => {
        isLoadingBB.value = false
        fetchData()
    }).catch((e: any) => {
        isLoadingBB.value = false
    })
}


function cetakLaporanPenerimaanHarian() {

const dari = H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
const sampai = H.formatDate(item.value.periode.end, 'YYYY-MM-DD')

H.printBlade('report/kasir/laporan-penerimaan-harian?pdf=true&tglAwal=' + dari + '&tglAkhir=' + sampai + '&idKasir=' + item.value.qKasir + '&idRuangan=' + (item.value.qRuanganK?item.value.qRuanganK.id:''));
}

function cetakLaporan() {
    const dari = H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
const sampai = H.formatDate(item.value.periode.end, 'YYYY-MM-DD')

H.printBlade('report/bendahara/get-lap-harian?pdf=true&tglAwal=' + dari + '&tglAkhir=' + sampai);
}


// function cetakLapHarian() {

// const dari = H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
// const sampai = H.formatDate(item.value.periode.end, 'YYYY-MM-DD')

// H.printBlade('report/bendahara/lap-penerimaan-harian?pdf=true&dari=' + dari + '&sampai=' + sampai);
// }

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiKasir&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}



const klikTab = (e: any) => {
    activeTab.value = e.index
    if (activeTab.value == 0) {
        fetchData()
    }
    if (activeTab.value == 1) {
        fetchDetail()
    }
}
const cari = () => {
    if (activeTab.value == 0) {
        fetchData()
    }
    if (activeTab.value == 1) {
        fetchDetail()
    }
}

watch(
    () => item.value.setoran,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            item.value.setoran = newValue
        }
        item.value.sisa2 = item.value.penerimaanTotal - parseFloat(newValue);
    }
)


fetchData()
fetchDetail()
fetchDropdown()



watch(currentPage.value, () => {
    fetchData()
})



</script>
  
  
  
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/timeline-css';



.fs-075 {
    font-size: 0.9rem;
}


.is-navbar {
    .form-layout {
        margin-top: 30px;
    }
}

.form-layout {
    // max-width: 740px;
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

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
    max-width: 30% !important;
}
</style>
  