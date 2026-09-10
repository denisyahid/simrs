<template>
  <div class="column is-12">
    <VCard>
      <div class="colum is-12">
        <div class="columns is-multiline">
          <div class="column">
            <span>Cari Data</span>
            <VField class="pt-2">
              <VControl>
                <VInput v-model="filterd" placeholder="Cari Data.." class="is-rounded"></VInput>
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <FileUpload name="demo[]" :multiple="false" @upload="onTemplatedUpload($event)" mode="advanced"
              :showUploadButton="false" :showCancelButton="true" @select="onSelectedFiles" chooseLabel="Pilih"
              cancelLabel="Batal" :maxFileSize="50000000">
              <template #header="{ chooseCallback, uploadCallback, clearCallback, files }">
                <div class="flex flex-wrap justify-content-between align-items-center flex-1 gap-2">
                  <div class="flex gap-2">
                    <Button @click="chooseCallback()" icon="pi pi-upload" rounded severity="info" class="mr-1"
                      outlined></Button>
                    <Button @click="clearCallback()" icon="pi pi-times" rounded outlined severity="danger"
                      :disabled="!files || files.length === 0"></Button>
                  </div>
                  <ProgressBar :value="totalSizePercent" :showValue="false"
                    :class="['md:w-20rem h-1rem w-full md:ml-auto', { 'exceeded-progress-bar': totalSizePercent > 100 }]">
                    <span class="white-space-nowrap">{{ totalSize
                    }}B / 50Mb</span>
                  </ProgressBar>
                </div>
              </template>
              <template #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback }">
                <div v-if="files.length > 0">

                  <div class="flex flex-wrap p-0 sm:p-5 gap-5">
                    <div :key="files[0].name + files[0].type + files[0].size"
                      class="card m-0 px-6 flex flex-column border-1 surface-border align-items-center gap-3">
                      <div>
                        <i class="fas fa-file-excel shadow-2 mr-2" aria-hidden="true"></i>
                      </div>
                      <span class="font-semibold">{{ files[0].name
                      }}</span>
                      <div class="ml-2">{{
                        formatSize(files[0].size)
                      }}
                        <Badge :value="valueProgress >= 99 ? 'Uploaded' : 'Pending'"
                          :severity="valueProgress >= 99 ? 'success' : 'warning'" class="ml-2 mr-2" />
                      </div>

                      <Button icon="pi pi-times" @click="onRemoveTemplatingFile(files[0], removeFileCallback, 0)" outlined
                        rounded severity="danger" />
                    </div>
                  </div>
                </div>
              </template>
              <template #empty>
                <p>Drag atau drop files untuk mengupload.</p>
              </template>
            </FileUpload>
          </div>
        </div>
      </div>
    </VCard>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="column" v-if="isLoading">
        <VPlaceloadWrap v-for="data in 10">
          <VPlaceload class="mx-2 mb-3" />
        </VPlaceloadWrap>
      </div>
      <div v-else-if="dataSourcefilter.length == 0">
        <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
          <template #image>
            <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
          </template>
        </VPlaceholderSection>
      </div>
      <div class="column is-12" v-else>
        <div class="column is-12">
          <h3 class="title is-5 mb-2 mr-1">Data Klaim BPJS </h3>
        </div>
        <DataTable :value="dataSourcefilter" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
          <Column field="KODE_RS" header="KODE RS" style="min-width: 150px" />
          <Column field="KELAS_RS" header="KELAS RS" style="min-width: 100px" />
          <Column field="KELAS_RAWAT" header="KODE RAWAT" style="min-width: 100px" />
          <Column field="KODE_TARIF" header="KODE TARIF" style="min-width: 100px" />
          <Column field="PTD" header="PDT" style="min-width: 60px" />
          <Column field="ADMISSION_DATE" header="ADMISSION DATE" style="min-width: 130px" />
          <Column field="DISCHARGE_DATE" header="DISCHARGE DATE" style="min-width: 130px" />
          <Column field="BIRTH_DATE" header="BIRTH DATE" style="min-width: 120px" />
          <Column field="BIRTH_WEIGHT" header="BIRTH WEIGHT" style="min-width: 120px" />
          <Column field="SEX" header="SEX" style="min-width: 50px" />
          <Column field="DISCHARGE_STATUS" header="DISCHARGE STATUS" style="min-width: 130px" />
          <Column field="DIAGLIST" header="DIAGLIST" style="min-width: 130px" />
          <Column field="PROCLIST" header="PROCLIST" style="min-width: 100px" />
          <Column field="ADL1" header="ADL1" style="min-width: 70px" />
          <Column field="ADL2" header="ADL2" style="min-width: 70px" />
          <Column field="IN_SP" header="IN_SP" style="min-width: 70px" />
          <Column field="IN_SR" header="IN_SP" style="min-width: 70px" />
          <Column field="IN_SI" header="IN_SI" style="min-width: 70px" />
          <Column field="IN_SD" header="IN_SD" style="min-width: 70px" />
          <Column field="INACBG" header="INACBG" style="min-width: 100px" />
          <Column field="SUBACUTE" header="SUBACUTE" style="min-width: 80px" />
          <Column field="CHRONIC" header="CHRONIC" style="min-width: 80px" />
          <Column field="SP" header="SP" style="min-width: 80px" />
          <Column field="SR" header="SR" style="min-width: 50px" />
          <Column field="SI" header="SI" style="min-width: 50px" />
          <Column field="SD" header="SD" style="min-width: 50px" />
          <Column field="DESKRIPSI_INACBG" header="DESKRIPSI INACBG" style="min-width: 300px" />
          <Column field="TARIF_INACBG" header="TARIF INACBG" style="min-width: 150px" />
          <Column field="TARIF_SUBACUTE" header="TARIF SUBACUTE" style="min-width: 150px" />
          <Column field="TARIF_CHRONIC" header="TARIF CHRONIC" style="min-width: 150px" />
          <Column field="DESKRIPSI_SP" header="DESKRIPSI SP" style="min-width: 150px" />
          <Column field="TARIF_SP" header="TARIF SP" style="min-width: 130px" />
          <Column field="DESKRIPSI_SR" header="DESKRIPSI SR" style="min-width: 130px" />
          <Column field="TARIF_SR" header="TARIF SR" style="min-width: 130px" />
          <Column field="DESKRIPSI_SI" header="DESKRIPSI SI" style="min-width: 150px" />
          <Column field="TARIF_SI" header="TARIF SI" style="min-width: 150px" />
          <Column field="DESKRIPSI_SD" header="DESKRIPSI SD" style="min-width: 150px" />
          <Column field="TARIF_SD" header="TARIF SD" style="min-width: 150px" />
          <Column field="TOTAL_TARIF" header="TOTAL TARIF" style="min-width: 160px" />
          <Column field="TARIF_RS" header="TARIF RS" style="min-width: 150px" />
          <Column field="TARIF_POLI_EKS" header="TARIF POLI EKS" style="min-width: 150px" />
          <Column field="LOS" header="LOS" style="min-width: 150px" />
          <Column field="ICU_INDIKATOR" header="ICU INDIKATOR" style="min-width: 50px" />
          <Column field="ICU_LOS" header="ICU_LOS" style="min-width: 150px" />
          <Column field="VENT_HOUR" header="VENT HOUR" style="min-width: 150px" />
          <Column field="NAMA_PASIEN" header="NAMA PASIEN" style="min-width: 200px" />
          <Column field="MRN" header="MRN" style="min-width: 150px" />
          <Column field="UMUR_TAHUN" header="UMUR TAHUN" style="min-width: 150px" />
          <Column field="UMUR_HARI" header="UMUR HARI" style="min-width: 200px" />
          <Column field="DPJP" header="DPJP" style="min-width: 200px" />
          <Column field="SEP" header="SEP" style="min-width: 200px" />
          <Column field="NOKARTU" header="NO KARTU" style="min-width: 150px" />
          <Column field="PAYOR_ID" header="PAYOR ID" style="min-width: 150px" />
          <Column field="CODER_ID" header="CODER ID" style="min-width: 150px" />
          <Column field="VERSI_INACBG" header="VERSI INACBG" style="min-width: 150px" />
          <Column field="VERSI_GROUPER" header="VERSI GROUPER" style="min-width: 150px" />
          <Column field="C1" header="C1" style="min-width: 150px" />
          <Column field="C2" header="C2" style="min-width: 350px" />
          <Column field="C3" header="C3" style="min-width: 150px" />
          <Column field="C4" header="C4" style="min-width: 250px" />
        </DataTable>
      </div>
    </VCard>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status success">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">TOTAL DATA</span>
              </div>
              <small class="text-bold-custom h-100">{{ dataSourcefilter.length }}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status warning">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">TOTAL TARIF</span>
              </div>
              <small class="text-bold-custom h-100">{{ H.formatRp(item.totalTarif, 'RP')
              }}</small>
            </VCardCustom>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <div class="columns is-multiline" style="align-items:right">
          <div class="column is-8">
          </div>
          <div class="column is-1">
            <VButton icon="lnir lnir-arrow-left rem-100" @click="kembali()" light dark-outlined>Kembali</VButton>
          </div>
          <div class="column is-1 mr-1">
            <VButton icon="lnir lnir-circle-plus rem-100" @click="collection()" color="warning">Collecting</VButton>
          </div>
          <div class="column is-1">
            <VButton icon="feather:save" @click="Save()" :loading="isLoadingSave" color="info">Simpan</VButton>
          </div>
        </div>
      </div>
    </VCard>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import { useHead } from '@vueuse/head';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';
import * as H from '/@src/utils/appHelper';
import { useApi } from '/@src/composable/useApi';
import moment from 'moment';

useHead({
  title: 'BPJS Klaim - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const isLoading: Boolean = ref(false)
const isLoadingUpload: Boolean = ref(false)
const isLoadingSave: Boolean = ref(false)
const totalSizePercent: Number = ref(0)
const totalSize: Number = ref(0)
const valueProgress: Number = ref(0)
const item: any = reactive({})
const dataSource: any = ref([])
const filterd = ref('')
const arr3 = ref([])
const router = useRouter()

const fetchData = async () => {

}

const onUpload = () => {

}
const onSelectedFiles = async (event: any) => {
  const files = event.files;

  if (files && files.length > 0) {
    const file = files[files.length - 1];


    if (file) {
      item.fileName = file.name;
      const reader = new FileReader();
      reader.onload = (e: any) => {
        const contents = e.target.result;
        const kodeRS = file.name.substr(0, 7);
        const arr1 = contents.split(kodeRS);
        const strJudul = arr1[0].split("\t");
        arr3.value = [];

        for (let i = 1; i <= arr1.length - 1; i++) {
          const strtea = arr1[i];
          const arr2 = strtea.split("\t");

          const arr4 = {
            'KODE_RS': 3174260,
            'KELAS_RS': arr2[1],
            'KELAS_RAWAT': arr2[2],
            'KODE_TARIF': arr2[3],
            'PTD': arr2[4],
            'ADMISSION_DATE': arr2[5],
            'DISCHARGE_DATE': arr2[6],
            'BIRTH_DATE': arr2[7],
            'BIRTH_WEIGHT': arr2[8],
            'SEX': arr2[9],
            'DISCHARGE_STATUS': arr2[10],
            'DIAGLIST': arr2[11],
            'PROCLIST': arr2[12],
            'ADL1': arr2[13],
            'ADL2': arr2[14],
            'IN_SP': arr2[15],
            'IN_SR': arr2[16],
            'IN_SI': arr2[17],
            'IN_SD': arr2[18],
            'INACBG': arr2[19],
            'SUBACUTE': arr2[20],
            'CHRONIC': arr2[21],
            'SP': arr2[22],
            'SR': arr2[23],
            'SI': arr2[24],
            'SD': arr2[25],
            'DESKRIPSI_INACBG': arr2[26],
            'TARIF_INACBG': arr2[27],
            'TARIF_SUBACUTE': arr2[28],
            'TARIF_CHRONIC': arr2[29],
            'DESKRIPSI_SP': arr2[30],
            'TARIF_SP': arr2[31],
            'DESKRIPSI_SR': arr2[32],
            'TARIF_SR': arr2[33],
            'DESKRIPSI_SI': arr2[34],
            'TARIF_SI': arr2[35],
            'DESKRIPSI_SD': arr2[36],
            'TARIF_SD': arr2[37],
            'TOTAL_TARIF': arr2[38],
            'TARIF_RS': arr2[39],
            'TARIF_POLI_EKS': arr2[40],
            'LOS': arr2[41],
            'ICU_INDIKATOR': arr2[42],
            'ICU_LOS': arr2[43],
            'VENT_HOUR': arr2[44],
            'NAMA_PASIEN': arr2[45],
            'MRN': arr2[46],
            'UMUR_TAHUN': arr2[47],
            'UMUR_HARI': arr2[48],
            'DPJP': arr2[49],
            'SEP': arr2[50],
            'NOKARTU': arr2[51],
            'PAYOR_ID': arr2[52],
            'CODER_ID': arr2[53],
            'VERSI_INACBG': arr2[54],
            'VERSI_GROUPER': arr2[55],
            'C1': arr2[56],
            'C2': arr2[57],
            'C3': arr2[58],
            'C4': arr2[59]
          };

          arr3.value.push(arr4);
        }
        let tarif = 0;
        dataSource.value = arr3.value;

      };
      reader.readAsText(file);
    } else {
      console.error('Invalid file.');
    }
  } else {
    console.error('No file selected.');
  }
}
const onTemplatedUpload = (e: any) => {

}
const formatSize = (bytes: any) => {
  if (bytes === 0) return "0 B";
  const k = 1024;
  const sizes = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};
const uploadEvent = (callback: any) => {
  totalSizePercent.value = totalSize.value / 10;
  isLoadingUpload.value = true
};
const onRemoveTemplatingFile = (file: any, removeFileCallback: any, index: any) => {
  removeFileCallback(index);
  totalSize.value -= parseInt(formatSize(file.size));
  totalSizePercent.value = totalSize.value / 10;
  dataSource.value = []
  valueProgress.value = 0
};
const dataSourcefilter = computed(() => {
  let tarifTotal = 0;
  const filteredData = dataSource.value.filter((data) => {
    return (
      (!filterd.value ||
        (data.NAMA_PASIEN && data.NAMA_PASIEN.match(new RegExp(filterd.value, 'i'))) ||
        (data.NOKARTU && data.NOKARTU.match(new RegExp(filterd.value, 'i'))) ||
        (data.SEP && data.SEP.match(new RegExp(filterd.value, 'i'))) ||
        (data.DPJP && data.DPJP.match(new RegExp(filterd.value, 'i'))))
    );
  });

  tarifTotal = filteredData.reduce((total, data) => total + parseFloat(data.TOTAL_TARIF), 0);
  item.totalTarif = tarifTotal;
  return filteredData;
});
const Save = async () => {
  let json = {
    'data': dataSourcefilter.value,
    'fileName': item.fileName
  }
  isLoadingSave.value = true;
  await useApi().post(
    `/piutang/save-bpjs-klaim`, json).then((response: any) => {
      isLoadingSave.value = false
    }).catch((e: any) => {
      isLoadingSave.value = false
    })
}
const kembali = () => {
  window.history.back()
}
const collection = () => {
  if (!item.fileName) {
    H.alert("warning", "Data Kosong !");
    return;
  }
  H.cacheHelper().set('periodeTransaksiPencatatanPiutangDaftarLayanan', {
    key : 'bpjs_klaim_inacbgs',
    fileName : item.fileName
  })
  router.push({
    name: 'module-piutang-collection-piutang',
    query: {
      start: moment(new Date()).format('YYYY-MM-DD'),
      end: moment(new Date()).format('YYYY-MM-DD')
    },
  })
}
</script>
