<template>
    <VCard>
        <h3>Ketersediaan Tempat Tidur</h3>
        <div class="columns is-multiline mt-2">
            <div class="column is-12">
                <TabView>
                    <TabPanel header="Ketersediaan Tempat Tidur">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <form class="form-layout is-stacked">
                                    <div class="form-outer">
                                        <div class="form-header stuck-header">
                                            <div class="form-body">
                                                <div class="form-section p-0">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-3">
                                                            <VField>
                                                                <VLabel>Start</VLabel>
                                                                <VControl>
                                                                    <VInput v-model="item.start" type="text"
                                                                        placeholder="Start" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-3">
                                                            <VField>
                                                                <VLabel>Limit</VLabel>
                                                                <VControl>
                                                                    <VInput v-model="item.limit" type="text"
                                                                        placeholder="Limit" />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                        <div class="column is-6">

                                                            <div class="column is-2 mt-4">
                                                                <VIconButton circle icon="feather:search" raised bold
                                                                    @click="fetchData()" :loading="isLoading"
                                                                    v-tooltip.bubble="'Cari'" class="mt-2-min">
                                                                </VIconButton>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="column is-12" v-if="isLoading">
                                <VPlaceloadWrap>
                                    <VPlaceload height="500px" width="100%" class="mx-2" />
                                </VPlaceloadWrap>
                            </div>
                            <div class="column is-12" v-else>
                                <DataTable v-model:filters="filters" :value="dataSource" paginator :rows="10" dataKey="no"
                                    filterDisplay="row"
                                    :globalFilterFields="['kodekelas', 'koderuang', 'namaruang', 'namakelas']"
                                    :class="`p-datatable-small`" showGridlines style="text-align: center;">
                                    <template #header>
                                        <div class="flex justify-content-between">
                                            <span class="p-input-icon-left">
                                              
                                                <InputText v-model="filters['global'].value" placeholder="Cari Data" />
                                                <i class="pi pi-search" />
                                            </span>
                                        </div>
                                    </template>
                                    <template #empty style="text-align: center;"> No data found. </template>
                                    <Column field="rownumber" header="No" style="text-align: center;"></Column>
                                    <Column field="koderuang" header="Kode Ruang" style="text-align: center;"></Column>
                                    <Column field="namaruang" header="Ruangan"></Column>
                                    <Column field="kodekelas" header="Kode Kelas" style="text-align: center;"></Column>
                                    <Column field="namakelas" header="Kelas"></Column>
                                    <Column field="kapasitas" header="Kapasitas" style="text-align: center;"></Column>
                                    <Column field="tersedia" header="Tersedia" style="text-align: center;"></Column>
                                    <Column field="tersediawanita" header="Tersedia Wanita" style="text-align: center;">
                                    </Column>
                                    <Column field="tersediapria" header="Tersedia Pria" style="text-align: center;">
                                    </Column>
                                    <Column field="tersediapriawanita" header="Tersedia Pria & Wanita"
                                        style="text-align: center;"></Column>
                                    <Column field="lastupdate" header="Tgl Update"></Column>
                                    <Column :exportable="false" header="Action" style="text-align: center">
                                        <template #body="slotProps">
                                            <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined
                                                raised v-tooltip.top="'Hapus'" @click="hapus(slotProps.data)">
                                            </VIconButton>
                                        </template>
                                    </Column>

                                </DataTable>
                            </div>
                        </div>
                    </TabPanel>
                    <TabPanel header="Update Ketersediaan Tempat Tidur">

                        <VButton type="button" class="mt-2 mb-3" rounded outlined color="primary" raised icon="feather:save"
                            :loading="isSave" @click="createKamar()"> Kirim Semua
                        </VButton>

                        <VButton type="button" class="mt-2 mb-3 ml-3" rounded outlined color="danger" raised icon="fas fa-trash"
                            :loading="isDelet" @click="hapusKamar()"> Hapus Data
                        </VButton>

                        <DataTable v-model:filters="filters" :value="dataRS" paginator :rows="10" dataKey="no"
                            filterDisplay="row" class="p-datatable-sm" editMode="cell" tableClass="editable-cells-table"
                            :class="`p-datatable-small`" showGridlines style="text-align: center;"
                            @cell-edit-complete="setNew">

                            <Column field="no" header="No" style="text-align: center;"></Column>
                            <Column field="koderuang" header="Kode Ruang" style="text-align: center;">
                                <template #body="slotProps">
                                    {{ slotProps.data.koderuang }}
                                </template>
                                <template #editor="{ data, field }">
                                    <InputText v-model="data[field]" autofocus />
                                </template>
                            </Column>
                            <Column field="namaruang" header="Ruangan">
                                <template #body="slotProps">
                                    {{ slotProps.data.namaruang }}
                                </template>
                                <template #editor="{ data, field }">
                                    <InputText v-model="data[field]" autofocus />
                                </template>

                            </Column>
                            <Column field="kodekelas" header="Kode Kelas" style="text-align: center;">
                                <template #body="slotProps">
                                    {{ slotProps.data.kodekelas }}
                                </template>
                                <template #editor="{ data, field }">
                                    <InputText v-model="data[field]" autofocus />
                                </template>
                            </Column>
                            <Column field="namakelas" header="Kelas">
                                <template #body="slotProps">
                                    {{ slotProps.data.namakelas }}
                                </template>
                                <template #editor="{ data, field }">
                                    <InputText v-model="data[field]" autofocus />
                                </template>
                            </Column>
                            <Column field="kapasitas" header="Kapasitas" style="text-align: center;">
                                <template #body="slotProps">
                                    {{ slotProps.data.kapasitas }}
                                </template>
                                <template #editor="{ data, field }">
                                    <InputText v-model="data[field]" autofocus />
                                </template>
                            </Column>
                            <Column field="tersedia" header="Tersedia" :rowEditor="true">
                                <template #body="slotProps">
                                    {{ slotProps.data.tersedia }}
                                </template>
                                <template #editor="{ data, field }">
                                    <InputText v-model="data[field]" autofocus />
                                </template>
                            </Column>
                            <Column field="tersediawanita" header="Tersedia Wanita" style="text-align: center;">
                                <template #body="slotProps">
                                    {{ slotProps.data.tersediawanita }}
                                </template>
                                <template #editor="{ data, field }">
                                    <InputText v-model="data[field]" autofocus />
                                </template>
                            </Column>
                            <Column field="tersediapria" header="Tersedia Pria" style="text-align: center;">
                                <template #body="slotProps">
                                    {{ slotProps.data.tersediapria }}
                                </template>
                                <template #editor="{ data, field }">
                                    <InputText v-model="data[field]" autofocus />
                                </template>
                            </Column>
                            <Column field="tersediapriawanita" header="Tersedia Pria & Wanita" style="text-align: center;">
                                <template #body="slotProps">
                                    {{ slotProps.data.tersediapriawanita }}
                                </template>
                                <template #editor="{ data, field }">
                                    <InputText v-model="data[field]" autofocus />
                                </template>
                            </Column>
                            <Column :exportable="false" header="Update" style="text-align: center">
                                <template #body="slotProps">
                                    <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined
                                        raised v-tooltip.top="'Update'" @click="simpan(slotProps.data)">
                                    </VIconButton>
                                </template>
                            </Column>
                        </DataTable>
                    </TabPanel>
                </TabView>
            </div>
        </div>
    </VCard>
</template>


<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useToaster } from '/@src/composable/toaster'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import { FilterMatchMode } from 'primevue/api'
import Calendar from 'primevue/calendar';
useHead({
    title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoading: any = ref(false)
const isSave: any = ref(false)
const isDelet: any = ref(false)
const item: any = reactive({
    limit: 10,
    start: 1,
})
const dataSource = ref([])
const dataRS = ref([])
const kodePPK = ref()
const isLoadingTT: any = ref(false)
const isLoadingSave: any = ref(false)

const expandedRows = ref([]);

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});

async function getPPK() {
    // const response = await useApi().get(`sysadmin/get/BPJS_kodePPKRujukan`)
    const response = await useApi().get(`sysadmin/get-settingdatafixed?namafield=BPJS_kodePPKRujukan`)
    kodePPK.value = response.data[0].nilaifield
}

const fetchDataRS = async () => {
    isLoading.value = true

    await useApi().get(
        `/bridging/bpjs/get-kamar-rs`
    ).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1

        });
        isLoading.value = false
        dataRS.value = response.data
        kodePPK.value = response.setting
    })
}

const fetchData = async () => {
    await getPPK()

    let json = {
        "url": `rest/bed/read/${kodePPK.value}/${item.start}/${item.limit}`,
        "jenis": "aplicare",
        "method": "GET",
        "data": null,
    }
    const res = await useApi().postBPJS(
        `/bridging/bpjs/tools`, json)
    if (res.metaData.code == 200) {
        res.response.list.forEach((element: any) => {

        });
        dataSource.value = res.response.list
    } else {
        H.alert('error', res.metaData.message)
    }
}



const simpan = async (e: any) => {
    var obj = {
        "url": `rest/bed/update/${kodePPK.value}`,
        "jenis": "aplicare",
        "method": "POST",
        "data": {
            "kodekelas": e.kodekelas,
            "koderuang": e.koderuang,
            "namaruang": e.namaruang,
            "kapasitas": e.kapasitas,
            "tersedia": e.tersedia,
            "tersediapria": e.tersediapria,
            "tersediawanita": e.tersediawanita,
            "tersediapriawanita": e.tersediawanita
        }
    }
    isLoadingSave.value = true;
    const data = useApi().postBPJS('/bridging/bpjs/tools', obj)
        .then((data: any) => {
            if (data.metaData.code == 200) {
                H.alert('success', data.metaData.message);
            } else {
                H.alert('error', data.metaData.message)
            }
        })
    isLoadingSave.value = false;
}


const hapus = (e: any) => {
    let json = {
        "url": `rest/bed/delete/${kodePPK.value}`,
        "jenis": "aplicare",
        "method": "POST",
        "data": {
            "kodekelas": e.kodekelas,
            "koderuang": e.koderuang,
        }
    }

    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {

        if (x.metaData.code == 200) {
            H.alert('success', x.metaData.message)
        } else {
            H.alert('error', x.metaData.message)
        }
    })
}

const createKamar = async () => {
    isSave.value = true
    for (let i = 0; i < dataRS.value.length; i++) {
        const element = dataRS.value[i];
        var json = {
            url: `rest/bed/create/${kodePPK.value}`,
            jenis: "aplicare",
            method: "POST",
            data: {
                "kodekelas": element.kodekelas,
                "koderuang": element.koderuang,
                "namaruang": element.namaruang,
                "kapasitas": element.kapasitas,
                "tersedia": element.tersedia,
                "tersediapria": element.tersediapria,
                "tersediawanita": element.tersediawanita,
                "tersediapriawanita": element.tersediawanita
            }
        }
        await useApi()
            .postNoMessage(`/bridging/bpjs/tools`, json)
            .then((x) => {
                if (x.metaData.code == 200) {
                    H.alert('success', x.metaData.message)
                } else {
                    H.alert('error', x.metaData.message)
                }
            })
    }
    isSave.value = false

}
const hapusKamar = async () => {
    isDelet.value = true
    let datas: any = []
    for (let i = 0; i < dataRS.value.length; i++) {
        const element = dataRS.value[i];
        var json = {
            url: `rest/bed/delete/${kodePPK.value}`,
            jenis: "aplicare",
            method: "POST",
            data: {
                "kodekelas": element.kodekelas,
                "koderuang": element.koderuang,
            }
        }
        await useApi()
            .postNoMessage(`/bridging/bpjs/tools`, json)
            .then((x) => {
                if (x.metaData.code == 200) {
                    H.alert('success', x.metaData.message)
                } else {
                    H.alert('error', x.metaData.message)
                }
            })
    }
    isDelet.value = false
}




const setNew = (event: any) => {
    let { data, field, newValue } = event
    console.log(field)
    switch (field) {
        case 'kapasitas':
        case 'tersedia':
        case 'kodekelas':
        case 'namakelas':
        case 'koderuang':
        case 'namaruang':
        case 'kapasitas':
        case 'tersediapria':
        case 'tersediawanita':
        case 'tersediapriawanita':

            if (newValue) data[field] = newValue;
            else event.preventDefault();
            break;

        default:
            if (newValue.trim().length > 0) data[field] = newValue;
            else event.preventDefault();
            break;
    }
};


// getPPK()
fetchDataRS()
fetchData()
</script>
<style lang="scss">
.control.has-icon {
    position: relative;
    width: 100%;
}

.field:not(:last-child) {
    margin-bottom: 0px !important;
}
</style>