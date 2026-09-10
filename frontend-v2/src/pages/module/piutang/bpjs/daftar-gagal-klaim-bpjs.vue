<template>
  <div class="column is-12">
    <VCard>
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
    </VCard>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="column is-12">
        <h3 class="title is-5 mb-2 mr-1">Data Gagal Klaim BPJS </h3>
      </div>
      <div class="column" v-if="isLoading">
        <VPlaceloadWrap v-for="data in 10">
          <VPlaceload class="mx-2 mb-3" />
        </VPlaceloadWrap>
      </div>
      <div v-else-if="dataSource.length == 0">
        <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
          <template #image>
            <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
          </template>
        </VPlaceholderSection>
      </div>
      <div class="column is-12" v-else>
        <DataTable :value="dataSource" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
          <Column field="NOSEP" header="NOSEP" style="min-width: 120px"></Column>
          <Column field="TGLSEP" header="TGLSEP" style="min-width: 70px"></Column>
          <Column field="NOKARTU" header="NO KARTU" style="min-width: 100px"></Column>
          <Column field="NMPESERTA" header="NAMA PESERTA" style="min-width: 120px"></Column>
          <Column field="RIRJ" header="RIRJ" style="min-width: 60px"></Column>
          <Column field="KDINACBG" header="KD INACBG" style="min-width: 100px"></Column>
          <Column field="BYPENGAJUAN" header="BY PENGAJUAN" style="min-width: 100px"></Column>
          <Column field="KETERANGAN" header="KETERANGAN" style="min-width: 200px"></Column>
        </DataTable>
      </div>
    </VCard>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="column is-12">
        <div class="columns is-multiline" style="align-items:right">
          <div class="column is-10">
          </div>
          <div class="column is-1">
            <VButton icon="lnir lnir-arrow-left rem-100" @click="kembali()" light dark-outlined>Kembali</VButton>
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
  title: 'Daftar Gagal Klaim BPJS - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const dataSource: any = ref([])
const totalSizePercent: Number = ref(0)
const totalSize: Number = ref(0)
const valueProgress: Number = ref(0)
const isLoading: Boolean = ref(false)
const isLoadingSave: Boolean = ref(false)
const item: any = reactive({})
const arr3: any = ref([])

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
            'NOSEP': arr2[1],
            'TGLSEP': arr2[2],
            'NOKARTU': arr2[3],
            'NMPESERTA': arr2[4],
            'RIRJ': arr2[5],
            'KDINACBG': arr2[6],
            'BYPENGAJUAN': arr2[7],
            'KETERANGAN': arr2[8]
          }
          arr3.value.push(arr4);
        }
        dataSource.value = arr3.value;
      }
      reader.readAsText(file);
    } else {
      H.alert('error', 'File Tidak Valid !');
    }
  } else {
    H.alert('warning', 'File Tidak Ditemukan !');
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
const kembali = () => {
  window.history.back()
}

const Save = async () => {
  let json =
  {
    data: dataSource.value,
    filename: item.filename
  }

  isLoadingSave.value = true
  await useApi().post(
    `/piutang/save-bpjs-klaim-gagal-hitung`, json).then((response: any) => {
      isLoadingSave.value = false
    }).catch((e: any) => {
      isLoadingSave.value = false
    })

  dataSource.value = [];
}
</script>
