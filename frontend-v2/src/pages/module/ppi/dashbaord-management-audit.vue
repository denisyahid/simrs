<template>
  <div class="columns is-multiline">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Dashboard Manajemen Audit</label>
      </div>
      <div class="column is-12">
        <div class="columns  is-multiline">
          <div class="column is-5">
            <VField label="Judul">
              <VControl icon="feather:search">
                <VInput type="text" v-model="item.title" placeholder="Cari Judul" />
              </VControl>
            </VField>
          </div>
          <div class="column btn-search">
            <VIconButton type="button" icon="feather:search" class="is-rounded" :loading="isLoading" @click="fetchData()"
              color="success">
            </VIconButton>
            <VIconButton type="button" color="warning" class="mt-1 ml-2" raised icon="fas fa-upload" @click="showModal()">
            </VIconButton>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <div class="tile-grid tile-grid-v1">
          <div class="columns is-multiline">
            <div v-for="key in 3" :key="key" class="column is-4" v-if="isLoading">
              <div class="tile-grid-item">
                <VFlexTableCell :column="{ grow: true, media: true }">
                  <VPlaceloadAvatar size="medium" width="20%" />
                  <VPlaceloadText :lines="2" width="20%" last-line-width="40%" class="mx-2" />
                </VFlexTableCell>
              </div>
            </div>
            <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6"
              v-else-if="dataSource.length == 0">
              <template #image>
                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>
            <div class="column is-4" v-for="(data, index) in dataSource" :key="index" v-else>
              <div class="tile-grid-item">
                <div class="tile-grid-item-inner">
                  <VAvatar size="small" :color="listColor[index]" :initials="data.initials" v-if="!data.photo" />
                  <VAvatar :picture="data.photo" size="small" color="success" v-else />
                  <div class="meta">
                    <span class="dark-inverted">{{ data.judul }}</span>
                    <span> {{ data.keterangan }}</span>
                  </div>
                  <VDropdown icon="feather:more-vertical" spaced right>

                    <template #content>
                      <a role="menuitem" class="dropdown-item is-media" @click="detail(data)">
                        <div class="icon">
                          <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                        </div>
                        <div class="meta">
                          <span>Lihat</span>
                          <span>Lihat Detail </span>
                        </div>
                      </a>
                      <a role="menuitem" class="dropdown-item is-media" @click="hapus(data)">
                        <div class="icon">
                          <i class="iconify" data-icon="feather:trash-2" aria-hidden="true"></i>
                        </div>
                        <div class="meta">
                          <span>Hapus</span>
                          <span>Hapus Data </span>
                        </div>
                      </a>
                      <a role="menuitem" class="dropdown-item is-media" @click="edit(data)">
                        <div class="icon">
                          <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                        </div>
                        <div class="meta">
                          <span>Edit</span>
                          <span>Edit Data </span>
                        </div>
                      </a>
                    </template>
                  </VDropdown>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>

  <!-- modal-->
  <VModal :open="modalAudit" title="Dashboard Management Audit" :noclose="false" size="large" actions="right"
    @close="modalAudit = false">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-12">
          <VField label="Cover">
            <VControl class="text-center">
              <VFilePond v-if="files.length" v-bind:files="files" class="profile-filepond" name="profile_filepond"
                :chunk-retry-delays="[500, 1000, 3000]" label-idle="<i class='lnil lnil-cloud-upload'></i>"
                :accepted-file-types="['image/png', 'image/jpeg', 'image/gif']" :image-preview-height="140"
                :image-resize-target-width="140" :image-resize-target-height="140" image-crop-aspect-ratio="1:1"
                style-panel-layout="compact circle" style-load-indicator-position="center bottom"
                style-progress-indicator-position="right bottom" style-button-remove-item-position="left bottom"
                style-button-process-item-position="right bottom" @addfile="onAddFile" @removefile="onRemoveFile" />
              <VFilePond v-else class="profile-filepond" name="profile_filepond" :chunk-retry-delays="[500, 1000, 3000]"
                label-idle="<i class='lnil lnil-cloud-upload'></i>"
                :accepted-file-types="['image/png', 'image/jpeg', 'image/gif']" :image-preview-height="140"
                :image-resize-target-width="140" :image-resize-target-height="140" image-crop-aspect-ratio="1:1"
                style-panel-layout="compact circle" style-load-indicator-position="center bottom"
                style-progress-indicator-position="right bottom" style-button-remove-item-position="left bottom"
                style-button-process-item-position="right bottom" @addfile="onAddFile" @removefile="onRemoveFile" />
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <VField label="Title">
            <VInput placeholder="masukan title" class="is-rounded" v-model="item.judul"></VInput>
          </VField>
        </div>
        <div class="column is-12">
          <VField label="Sumber">
            <VInput placeholder="masukan sumber" class="is-rounded" v-model="item.sumber"></VInput>
          </VField>
        </div>
        <div class="column is-12">
          <VField label="isi">
            <VTextarea placeholder="masukan isi" class="is-rounded" row="2" v-model="item.isi"></VTextarea>
          </VField>
        </div>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="simpan()" :loading="isLoadingSave" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>
  <!-- end modal-->
  <VModal :open="modalDetail" :title="'Detail ' + item.title" size="medium" actions="right" @close="modalDetail = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField label="Judul">
              <VAvatar size="small" :color="listColor[1]" :initials="'U'" v-if="!item.photo" />
              <VAvatar :picture="item.photo" size="small" color="success" v-else />
            </VField>
          </div>
          <div class="column is-12">
            <VField label="Judul">
              <VLabelLarge>
                {{ item.title }}
              </VLabelLarge>
            </VField>
            <VField label="Isi">
              <VLabelLarge>
                {{ item.content }}
              </VLabelLarge>
            </VField>
          </div>
        </div>
      </form>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import { useHead } from '@vueuse/head';
import { useApi } from '/@src/composable/useApi';
import * as H from '/@src/utils/appHelper';
import { useThemeColors } from '/@src/composable/useThemeColors'


useHead({
  title: 'Dashbaord Managemnt Audit - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let listColor: any = ref(Object.keys(useThemeColors()))
const item: any = reactive({});
const isLoading: any = ref(false)
const dataSource: any = ref([])
const modalAudit: any = ref(false)
const modalDetail: any = ref(false)
const isLoadingSave: any = ref(false)
const files = ref([])
const fileFoto: any = ref(null)

const fetchData = async () => {
  isLoading.value = true;
  let title = item.title ?? ''
  await useApi().get(`/ppi/get-riwayat?search=${title}`).then((response: any) => {
    response.map((element: any) => {
      let ini = element.judul.split(' ')
      let init = element.judul.substr(0, 1)
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1)
      }
      element.initials = init
    })
    dataSource.value = response;
    isLoading.value = false;
  });
}


const showModal = () => {
  modalAudit.value = true
}
const onAddFile = (error: any, fileInfo: any) => {
  if (error) {
    console.error(error)
    return
  }

  const _file = fileInfo.file as File
  if (_file) {
    fileFoto.value = _file
  }
}

const onRemoveFile = (error: any, fileInfo: any) => {
  if (error) {
    console.error(error)
    return
  }

  fileFoto.value = null
}
const simpan = async () => {
  if (!item.judul) {
    H.alert("warning", "Judul Harus Diisi !");
    return;
  }
  if (!item.sumber) {
    H.alert("warning", "Sumber Harus Diisi !");
    return;
  }
  if (!item.isi) {
    H.alert("warning", "Isi Harus Diisi !");
    return;
  }
  // if (!fileFoto.value) {
  //   H.alert("warning", "Foto Belum Dipilih !");
  //   return;
  // }
  isLoadingSave.value = true;
  const formData = new FormData();
  formData.append('norec', item.norec ?? '');
  if (fileFoto.value != null) {
    let img: any = await blobToBase64(fileFoto.value)
    formData.append('file', img);
  }
  formData.append('judul', item.judul);
  formData.append('isi', item.isi);
  formData.append('keterangan', item.sumber);
  console.log(fileFoto.value);
  formData.forEach((value, key) => {
    console.log(`${key}: ${value}`);
  });

  useApi().postNoMessage(
    `/ppi/save-data-pmkp`, formData).then((response: any) => {
      modalAudit.value = false
      H.alert("success", response.message)
      fetchData();
    }).catch((error: any) => {
      H.alert("error", error.message)
    });
  isLoadingSave.value = false;
  clear();
}
const hapus = async (data: any) => {
  let json = {
    norec: data.norec
  }
  useApi().post(`/ppi/hapus-riwayat`, json).then((response: any) => {
    fetchData();
  })
}
const clear = () => {
  item.norec = ''
  item.judul = ''
  item.isi = ''
  item.sumber = ''
  fileFoto.value = ''
}
const edit = async (data: any) => {
  if (data.photo) {
    let path = 'berkas_ppi/' + data.image
    let file: any = await H.getFileBE(path);
    fileFoto.value = file
    let img: any = await blobToBase64(file)
    files.value = [img]
  }
  item.norec = data.norec
  item.sumber = data.keterangan
  item.isi = data.isi
  item.judul = data.judul
  modalAudit.value = true
}
const blobToBase64 = (blob: any) => {
  return new Promise((resolve, _) => {
    const reader = new FileReader();
    reader.onloadend = () => resolve(reader.result);
    reader.readAsDataURL(blob);
  });
}
fetchData();
const detail = async (data: any) => {
  console.log(JSON.stringify(data));
  modalDetail.value = true;
  item.title = data.judul;
  item.content = data.isi;
  item.keterangan = data.keterangan;
  item.image = data.photo
}
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.btn-search {
  display: flex;
  align-items: center;
  margin-top: 14px;
}

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsla(19deg, 100%, 75%, 0.72) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;

  .title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 8px;
    font-weight: 600;
  }
}

.tile-grid-v1 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 18px;

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      .meta {
        margin-left: 10px;
        line-height: 1.2;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 0.9rem;
          }

          &:nth-child(2) {
            color: var(--light-text);
            font-size: 0.5 rem;
          }
        }
      }

      .dropdown {
        position: relative;
        margin-left: auto;
      }
    }
  }
}
</style>
