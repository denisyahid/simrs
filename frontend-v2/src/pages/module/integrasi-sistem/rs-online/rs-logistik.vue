<template>
    <VCard>
        <h3>Logistik</h3>
        <div class="columns is-multiline mt-2">
            <div class="column is-12">

                <div class="column is-12" v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="500px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-12" v-else>
                    <DataTable v-model:filters="filters" :value="dataSource" paginator :rows="10" dataKey="no"
                        filterDisplay="row" :globalFilterFields="['kodekelas', 'koderuang', 'namaruang', 'namakelas']"
                        :class="`p-datatable-small`" showGridlines style="text-align: center;">
                        <template #header>
                            <div class="flex justify-content-between">
                                <span class="p-input-icon-left">
                                    <InputText v-model="filters['global'].value" placeholder="Cari Data" />
                                    <i class="pi pi-search" />
                                </span>
                                <VButton color="warning" class="mr-4 mb-3" icon="fas fa-plus" raised @click="tambahData()">
                                    Tambah Data </VButton>
                            </div>
                        </template>
                        <Column field="no" header="No" style="text-align: center;"></Column>
                        <Column field="tglupdate" header="Tanggal Update"></Column>
                        <Column field="kebutuhan" header="Kebutuhan" style="min-width: 200px"></Column>
                        <Column field="jumlah_eksisting" header="Jumlah Eksisting" style="min-width: 200px"></Column>
                        <Column field="jumlah" header="Jumlah" style="min-width: 100px"></Column>
                        <Column field="jumlah_diterima" header="Jumlah Diterima" style="min-width: 100px"></Column>
                        <Column :exportable="false" header="Action">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                                    v-tooltip.top="'Hapus'" @click="hapus(slotProps.data)">
                                </VIconButton>
                            </template>
                        </Column>



                    </DataTable>
                </div>
            </div>

        </div>

    </VCard>

    <Dialog v-model:visible="modalInput" modal header="Form Logistik" :style="{ width: '30vw' }">
        <div class="columns is-multiline">

            <div class="column is-12">
                <VField label="Kebutuhan" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0">
                    <VControl icon="fas fa-book" fullwidth class="prime-auto-select">
                        <MultiSelect v-model="item.kebutuhan" display="chip" :options="listKebutuhan"
                            optionLabel="kebutuhan" placeholder="Kebutuhan" optionValue="id" class="is-rounded w-100"
                            :maxSelectedLabels="3" />

                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Jumlah Eksisting</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.jumlah_eksisting" type="text" class="input is-rounded" placeholder=" " />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Jumlah</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.jumlah" type="text" class="input is-rounded" placeholder=" " />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4">
                <VField>
                    <VLabel>Jumlah Terima</VLabel>
                    <VControl icon="feather:bookmark">
                        <input v-model="item.jumlah_diterima" type="text" class="input is-rounded" placeholder=" " />
                    </VControl>
                </VField>
            </div>
        </div>
        <template #footer>
            <VButton color="danger" icon="pi pi-times" outlined raised @click="modalInput = false"> Batal </VButton>
            <VButton color="primary" icon="pi pi-check" raised @click="simpanKebutuhan()" :loading="btnLoadSimpan"> Simpan
            </VButton>
        </template>
    </Dialog>
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
import AutoComplete from 'primevue/autocomplete';
import MultiSelect from 'primevue/multiselect';
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import Dialog from 'primevue/dialog';

import { FilterMatchMode } from 'primevue/api'
import Calendar from 'primevue/calendar';
useHead({
    title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoading: any = ref(false)

const item: any = reactive({
    filterDate: {
        start: new Date(),
        end: new Date()
    },
    limit: 10,
    start: 1,
})
const dataSource = ref([])
const dataRS = ref([])

const isLoadingTT: any = ref(false)
const isLoadingSave: any = ref(false)
const btnLoadSimpan = ref(false)
const listKebutuhan = ref([])
const modalInput = ref(false)

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});

const fetchDataLogistik = async () => {
    let json = {
        "url": "Fasyankes/apd",
        "method": "GET",
        "jenis": "sirsonlinev3",
        "data": null
    }
    const res = await useApi().postNoMessage(
        `/bridging/kemenkes/tools`, json)
    if (res.metaData.code == 200) {
        res.response.apd.forEach((element: any) => {
            element.no = element + 1
        });
        dataSource.value = res.response.apd
    } else {
        H.alert('error', res.metaData.message)
    }
}

const loadcombo = async () => {
    let json = {
        "url": "Referensi/kebutuhan_apd",
        "method": "GET",
        "jenis": "sirsonlinev3",
        "data": null
    }
    const res = await useApi().postNoMessage(`/bridging/kemenkes/tools`, json).then(function (e) {
        listKebutuhan.value = e.data.kebutuhan_apd
    })
}

const tambahData = () => {
    modalInput.value = true
}

const simpanKebutuhan = () => {

    if (!item.kebutuhan) {
        H.alert('error', 'Kebutuhan Wajib diisi')
        return
    }

    if (!item.jumlah_eksisting) {
        H.alert('error', 'Jumlah Eksisting Wajib diisi')
        return
    }
    if (!item.jumlah) {
        H.alert('error', 'Jumlah Eksisting Wajib diisi')
        return
    }
    if (!item.jumlah_diterima) {
        H.alert('error', 'Jumlah Diterima Wajib diisi')
        return
    }

    var json = {
        "url": "Fasyankes/apd",
        "method": "POST",
        "jenis": "sirsonlinev3",
        "data": {
            "id_kebutuhan": item.kebutuhan,
            "jumlah_eksisting": item.jumlah_eksisting,
            "jumlah": item.jumlah,
            "jumlah_diterima": item.jumlah_diterima
        }
    }
    isLoadingSave.value = true;

    const data = useApi().postNoMessage('/bridging/kemenkes/tools', json)
        .then((data: any) => {
            if (data.metaData.code == 200) {
                H.alert('success', data.metaData.message);
            } else {
                H.alert('error', data.metaData.message)
            }
            isLoadingSave.value = false;
            modalInput.value = false
            fetchDataLogistik()
        })

}

const hapus = () => {
    var json = {
        "url": "Fasyankes/apd",
        "method": "DELETE",
        "jenis": "sirsonlinev3",
        "data": {
            "id_kebutuhan": item.kebutuhan
        }
    }

    useApi().postBPJS('/bridging/kemenkes/tools', json).then((x) => {
        if (x.metaData.code == 200) {
            H.alert('success', x.metaData.message)
        } else {
            H.alert('error', x.metaData.message)
        }
        fetchDataLogistik()
    })

}


fetchDataLogistik()
loadcombo()

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
