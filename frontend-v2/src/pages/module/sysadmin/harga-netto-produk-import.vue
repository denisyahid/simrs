
<template>
    <div class="columns">
        <div class="column is-12 form-layout is-stacked">
            <div class=" form-outer">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Import Tarif</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined>
                                    Batal
                                </VButton>
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isSimpan" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-body">
                    <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-12 p-4">
                                        <div class="columns is-multiline">
                                            <div class="column is-12 mt-4-min">
                                                <VButton rounded color="warning" class="" icon="feather:download" raised
                                                    bold @click="downloadTemplate()">
                                                    Download Template
                                                </VButton>
                                                <div class="dataTable-bottom mt-2">
                                                    <div class="dataTable-info" style="font-style:italic"> *Note : Template
                                                        digunakan untuk menyamakan data, agar saat di upload tidak terjadi
                                                        kesalahan memasukkan data.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="column is-12 mb-5">
                                                <Fieldset legend="Upload Excel" :toggleable="true" :collapsed="false">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-12 mb-2-min">
                                                            <ProgressBar :value="valueProgress" style="height: 15px" />
                                                        </div>
                                                        <div class="column is-6">
                                                            <FileUpload name="demo[]" :multiple="false"
                                                                @upload="onTemplatedUpload($event)" mode="advanced"
                                                                :showUploadButton="false" :showCancelButton="true"
                                                                @select="onSelectedFiles"
                                                                chooseLabel="Pilih"
                                                                cancelLabel="Batal"
                                                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                                                :maxFileSize="50000000">
                                                                <template
                                                                    #header="{ chooseCallback, uploadCallback, clearCallback, files }">
                                                                    <div
                                                                        class="flex flex-wrap justify-content-between align-items-center flex-1 gap-2">
                                                                        <div class="flex gap-2">
                                                                            <Button @click="chooseCallback()"
                                                                                icon="pi pi-upload" rounded severity="info" class="mr-1"
                                                                                outlined></Button>
                                                                            <!-- <Button @click="uploadEvent(uploadCallback)"
                                                                                icon="pi pi-cloud-upload" rounded outlined
                                                                                severity="success"
                                                                                :disabled="!files || files.length === 0"></Button> -->
                                                                            <Button @click="clearCallback()"
                                                                                icon="pi pi-times" rounded outlined
                                                                                severity="danger"
                                                                                :disabled="!files || files.length === 0"></Button>
                                                                        </div>
                                                                        <ProgressBar :value="totalSizePercent"
                                                                            :showValue="false"
                                                                            :class="['md:w-20rem h-1rem w-full md:ml-auto', { 'exceeded-progress-bar': totalSizePercent > 100 }]">
                                                                            <span class="white-space-nowrap">{{ totalSize
                                                                            }}B / 50Mb</span>
                                                                        </ProgressBar>
                                                                    </div>
                                                                </template>
                                                                <template
                                                                    #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback }">
                                                                    <div v-if="files.length > 0">

                                                                        <div class="flex flex-wrap p-0 sm:p-5 gap-5">
                                                                            <div :key="files[0].name + files[0].type + files[0].size"
                                                                                class="card m-0 px-6 flex flex-column border-1 surface-border align-items-center gap-3">
                                                                                <div>
                                                                                    <i class="fas fa-file-excel shadow-2 mr-2"
                                                                                        aria-hidden="true"></i>
                                                                                    <!-- <img role="presentation"
                                                                                        :alt="file.name"
                                                                                        :src="'/images/avatars/svg/product.svg'" width="50"
                                                                                        height="50" class="shadow-2" /> -->
                                                                                </div>
                                                                                <span class="font-semibold">{{ files[0].name
                                                                                }}</span>
                                                                                <div class="ml-2">{{
                                                                                    formatSize(files[0].size)
                                                                                }}
                                                                                    <Badge
                                                                                        :value="valueProgress >= 99 ? 'Uploaded' : 'Pending'"
                                                                                        :severity="valueProgress >= 99 ? 'success' : 'warning'"
                                                                                        class="ml-2 mr-2" />
                                                                                </div>

                                                                                <Button icon="pi pi-times"
                                                                                    @click="onRemoveTemplatingFile(files[0], removeFileCallback, index)"
                                                                                    outlined rounded severity="danger" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                                <template #empty>
                                                                    <p>Drag atau drop files untuk mengupload.</p>
                                                                </template>
                                                            </FileUpload>
                                                        </div>
                                                        <div class="column is-6">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <VField label=" Jenis Pelayanan"
                                                                        class="is-rounded-select is-autocomplete-select required-vfield"
                                                                        v-slot="{ id }">
                                                                        <VControl icon="fas fa-bed" fullwidth
                                                                            class="prime-auto-select">
                                                                            <Dropdown v-model="item.jenispelayanan"
                                                                                :options="d_JenisPelayanan"
                                                                                :optionLabel="'jenispelayanan'"
                                                                                class="is-rounded"
                                                                                placeholder="Jenis Pelayanan "
                                                                                style="width: 100%;" :filter="true"
                                                                                showClear />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>
                                                                <div class="column is-12">
                                                                    <VField label="Penjamin"
                                                                        class="is-rounded-select is-autocomplete-select"
                                                                        v-slot="{ id }">
                                                                        <VControl icon="fas fa-hospital" fullwidth
                                                                            class="prime-auto-select">
                                                                            <Dropdown v-model="item.rekanan"
                                                                                :options="d_Rekanan"
                                                                                :optionLabel="'namarekanan'"
                                                                                class="is-rounded" placeholder="Penjamin "
                                                                                style="width: 100%;" :filter="true"
                                                                                showClear />
                                                                        </VControl>
                                                                    </VField>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="column is-12">

                                                            <DataTable :value="dataSource" :paginator="true" :rows="10"
                                                                :rowsPerPageOptions="[5, 10, 25]"
                                                                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                                                responsiveLayout="stack" breakpoint="960px"
                                                                sortMode="multiple" v-model:expandedRows="expandedRows"
                                                                dataKey="no" :rowHover="true"
                                                                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

                                                                <Column expander style="width: 5rem" />
                                                                <Column :exportable="false" header="#">
                                                                    <template #body="slotProps">
                                                                        <VIconButton type="button" icon="fas fa-trash"
                                                                            class="mr-3 btn-slim-2" color="danger" circle
                                                                            outlined raised v-tooltip.top="'Hapus'"
                                                                            @click="deleterow(slotProps.data)">
                                                                        </VIconButton>
                                                                    </template>
                                                                </Column>
                                                                <Column field="no" header="No"></Column>
                                                                <Column field="namaproduk" header="Nama Produk"
                                                                    :sortable="true"></Column>
                                                                <Column field="kelas" header="Kelas" :sortable="true">
                                                                </Column>
                                                                <Column field="hargasatuan" header="Harga Satuan">
                                                                    <template #body="slotProps">
                                                                        {{ formatRp(slotProps.data.hargasatuan, '') }}
                                                                    </template>
                                                                </Column>
                                                                <!-- <Column field="jenispelayanan" header="Jenis Pelayanan">
                                                                </Column> -->
                                                                <!-- <Column field="penjamin" header="Penjamin">
                                                                </Column> -->
                                                                <template #expansion="slotProps">
                                                                    <DataTable :value="slotProps.data.details">
                                                                        <Column field="komponenharga" header="Komponen"
                                                                            sortable></Column>
                                                                        <Column field="hargasatuan" header="Harga" sortable>
                                                                            <template #body="slotProps2">
                                                                                {{ formatRp(slotProps2.data.hargasatuan,
                                                                                    '') }}
                                                                            </template>
                                                                        </Column>
                                                                        <template #footer> Total {{
                                                                            formatRp(slotProps.data.totalkomponen, '') }}
                                                                        </template>
                                                                    </DataTable>
                                                                </template>
                                                            </DataTable>
                                                            <div class="dataTable-bottom mt-2">
                                                                <div class="dataTable-info">{{ dataSource.length }} total
                                                                    data.
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </Fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">

import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
    ref,
    computed,
    defineComponent,
    watch,
    nextTick,
    onMounted,
    reactive,
    watchEffect
} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { formatRp } from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import * as H from '/@src/utils/appHelper'
import Fieldset from 'primevue/fieldset';
import ProgressBar from 'primevue/progressbar';
import FileUpload from 'primevue/fileupload';
import Dropdown from 'primevue/dropdown';
import Badge from 'primevue/badge';
import * as XLSX from "xlsx";
import { useUserSession } from '/@src/stores/userSession'

const TITLE_PAGE = 'Import Harga Netto Produk By Kelas'
useHead({
    title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)

const expandedRows: any = ref([]);
const { y } = useWindowScroll()
const router = useRouter()
const totalSize = ref(0);
const totalSizePercent = ref(0);
const valueProgress: any = ref(0)
const isSimpan: any = ref(false)
const komponenharga: any = ref([])
const isStuck = computed(() => {
    return y.value > 30
})
const d_JenisPelayanan: any = ref([])
const d_Rekanan: any = ref([])
const dataSource: any = ref([])
const item: any = reactive({})
const downloadTemplate = () => {
    // H.open('sysadmin/master-harga-netto-produk-by-kelas-download');
    window.open(import.meta.env.VITE_API_BASE_URL + 'sysadmin/master-harga-netto-produk-by-kelas-download?token=' + useUserSession().token, '_blank');
}

function step() {

}
const simpan = async () => {

    let komp = []
    for (let y = 0; y < komponenharga.value.length; y++) {
        const element = komponenharga.value[y];
        komp.push({ id: null, komponenharga: element })
    }
    isSimpan.value = true
    valueProgress.value = 0;
    let n = 0
    let response = null
    for (let x = 0; x < dataSource.value.length; x++) {
        let element = dataSource.value[x];
        n = (x + 1) * 100 / dataSource.value.length
        response = await useApi().postNoMessage(`/sysadmin/import-harga-netto-produk-by-kelas`, {
            'komponenharga': komp,
            'komponenhargaIn': komponenharga.value,
            'import': element,
        })
        valueProgress.value = n;
    }
    if (response != null) {
        H.alert('success', 'Sukses');
    }
    isSimpan.value = false
}
const deleterow = async (e: any) => {
    for (var i = dataSource.value.length - 1; i >= 0; i--) {
        if (dataSource.value[i].no == e.no) {
            dataSource.value.splice(i, 1);
        }
    }
}
const onSelectedFiles = async (filez: any) => {

    const file = filez
        .files[filez
            .files.length-1];

    if (file.type != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
        H.alert('error', 'File yang diizinkan dalam bentuk format Excel.')
        return;
    }
    if (file) {
        totalSize.value = parseInt(formatSize(file.size));
        totalSizePercent.value = totalSize.value / 10;
        const reader = new FileReader();

        reader.onload = (e: any) => {
            const bstr = e.target.result;
            const wb = XLSX.read(bstr, { type: 'binary' });
            const wsname = wb.SheetNames[0];
            const ws = wb.Sheets[wsname];
            const data = XLSX.utils.sheet_to_json(ws, { header: 1 });
            setToGRID(data)
        }

        reader.readAsBinaryString(file);
    }
}

const formatSize = (bytes: any) => {
    if (bytes === 0) return "0 B";
    const k = 1024;
    const sizes = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};
const back = () => {
    window.history.back()
}
const onRemoveTemplatingFile = (file: any, removeFileCallback: any, index: any) => {
    removeFileCallback(index);
    totalSize.value -= parseInt(formatSize(file.size));
    totalSizePercent.value = totalSize.value / 10;

    dataSource.value = []
    valueProgress.value = 0
};
const onTemplatedUpload = (e: any) => {

};
const uploadEvent = (callback: any) => {
    totalSizePercent.value = totalSize.value / 10;
    callback();
};
const setToGRID = (e: any) => {
    valueProgress.value = 0
    dataSource.value = []
    komponenharga.value = getKomponenHarga(e)
    let head: any = []
    let dataTARIF = transposeArray(e)

    let kelas: any = getKelas(e)
    let pemisahLength = 0
    for (let x = 0; x < dataTARIF.length; x++) {
        const element = dataTARIF[x];
        let status = false
        for (let y = 0; y < element.length; y++) {
            const element2 = element[y];
            if (element2 != undefined) {
                status = true
            }
        }
        if (!status) {
            pemisahLength = x
        }
    }

    // layanan 
    for (let x = 0; x < dataTARIF[1].length; x++) {
        const element = dataTARIF[1][x];
        if (element && element != "NAMA PELAYANAN") {
            for (let u = 0; u < kelas.length; u++) {
                const elementKelas = kelas[u];
                head.push({
                    index: x,
                    namaproduk: element,
                    kelas: elementKelas,
                    penjamin: item.rekanan ? item.rekanan.namarekanan : null,
                    jenispelayanan: item.jenispelayanan ? item.jenispelayanan.jenispelayanan : null,
                    penjaminfk: item.rekanan ? item.rekanan.id : null,
                    jenispelayananfk: item.jenispelayanan ? item.jenispelayanan.id : null,
                    hargasatuan: null,
                    totalkomponen: null,
                    details: []
                })
            }
        }
    }
    for (let x = 0; x < dataTARIF.length; x++) {
        const element = dataTARIF[x];
        if (x > 1 && x < pemisahLength) {
            for (let z = 0; z < head.length; z++) {
                const element2 = head[z];
                if (element2.kelas == element[1]) {
                    element2.hargasatuan = element[element2.index]
                }
            }
        }
        if (x > pemisahLength) {
            for (let k = 0; k < head.length; k++) {
                const elementHead = head[k];
                if (elementHead.kelas == element[1]) {
                    elementHead.details.push({
                        'komponenharga': element[0],
                        'kelas': element[1],
                        'hargasatuan': element[elementHead.index]
                    })
                }
            }
        }
    }
    for (let k = 0; k < head.length; k++) {
        head[k].no = k + 1
        head[k].totalkomponen = 0;
        for (let y = 0; y < head[k].details.length; y++) {
            const element = head[k].details[y];
            head[k].totalkomponen = head[k].totalkomponen + element.hargasatuan;
        }
    }
    // console.log(dataTARIF)
    // console.log(pemisahLength)
    // console.log(komponenharga)
    dataSource.value = head
}
const getKomponenHarga = (e: any) => {
    let data: any = []
    for (let x = 0; x < e.length; x++) {
        const element = e[x];
        if (x == 0) {
            for (let y = 0; y < element.length; y++) {
                const element2 = element[y];
                if (element2 && (element2 != 'NO' && element2 != 'NAMA PELAYANAN')) {
                    data.push(element2)
                }
            }
        }
    }
    console.log(data)
    return [...new Set(data)]
}
const getKelas = (e: any) => {
    let data: any = []
    for (let x = 0; x < e.length; x++) {
        const element = e[x];
        if (x == 1) {
            for (let y = 0; y < element.length; y++) {
                const element2 = element[y];
                if (element2) {
                    data.push(element2)
                }
            }
        }
    }
    return [...new Set(data)]
}
const transposeArray = (array: any) => {
    const rows = array.length;
    const cols = array[0].length;
    const transposedArray = new Array(cols);

    for (let i = 0; i < cols; i++) {
        transposedArray[i] = new Array(rows);
    }

    for (let i = 0; i < rows; i++) {
        for (let j = 0; j < cols; j++) {
            transposedArray[j][i] = array[i][j];
        }
    }
    return transposedArray;
}
const listDropdown = async () => {
    const response = await useApi().get(
        `/sysadmin/master-harga-netto-produk-by-kelas-dropdown-import`)
    d_Rekanan.value = response.namarekanan
    d_JenisPelayanan.value = response.jenispelayanan
    if (response.jenispelayanan.length > 0) {
        item.jenispelayanan = response.jenispelayanan[0]
    }
}
listDropdown()
</script>
    
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/inacbgs/inacbgs-detail';
</style>
    
    