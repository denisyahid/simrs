<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <!-- <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header"> -->
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-2">
                <VButton rounded icon="feather:plus" raised bold @click="add()" color="success" outlined
                  :loading="isLoading" class="mr-2">Upload File </VButton>
              </div>
              <div class="column is-10">
                <div class="control">
                  <input type="text" v-model="filter" class="input" placeholder="Cari..." />
                  <button class="searcv-button" type="button" :loading="isLoading" @click="loadRiwayat">
                    <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      <!-- </div> -->

      <div class="column is-12">
        <div class="columns is-multiline mt-5" v-if="isLoading">
          <VPlaceloadText :lines="1" class="p-2" />
          <div class="column is-12" v-for="key in 2" :key="key">
            <VPlaceloadWrap>
              <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
              <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
              <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
              <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
            </VPlaceloadWrap>
          </div>
        </div>
        <div class="columns is-multiline" v-else>
          <div class="column is-12" v-if="dataSource.length">
            <div class="columns is-multiline">
              <div class="column is-12">
                <BerkasPasienView :data="dataSource" @edit="edit" @hapus="hapus" @lihat="lihat" :hide="false">
                </BerkasPasienView>
              </div>
            </div>
          </div>
          <div class="column is-12" v-else>
            <div class="page-placeholder">
              <div class="placeholder-content">
                <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
                <h3>{{ H.assets().notFound }}</h3>
                <p class="is-larger">
                  {{ H.assets().notFoundSubtitle }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <Dialog v-model:visible="modalInput" modal header="Upload" :style="{ width: '30vw' }">
    <div class="column is-12">
      <VField class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
        <VLabel class="required-field">File</VLabel>
        <VControl icon="fas fa-sticky-note" fullwidth class="prime-auto-select">
          <Dropdown v-model="input.namafile" :options="d_Berkas" :optionLabel="'nama'" class="is-rounded"
            placeholder="File" style="width: 100%;" :filter="true" showClear />
        </VControl>
      </VField>
    </div>
    <div class="column is-12">
      <VField>
        <VLabel class="required-field">Nama File</VLabel>
        <VControl icon="feather:bookmark">
          <input v-model="input.nama" type="text" class="input is-rounded" />
        </VControl>
      </VField>
    </div>
    <div class="column is-12">
      <VField>
        <VLabel class="required-field">Author</VLabel>
        <VControl icon="feather:bookmark">
          <VInput v-model="input.author" type="text" class="input is-rounded" />
        </VControl>
      </VField>
    </div>
    <div class="column is-12">
      <VField>
        <VLabel>Keterangan</VLabel>
        <VControl>
          <VTextarea v-model="input.keterangan" rows="3" placeholder="Keterangan">
          </VTextarea>
        </VControl>
      </VField>
    </div>
    <div class="column is-12">
      <FileUpload v-model="filePasien" mode="basic" name="demo" accept="application/pdf,image/*" @upload="onUpload"
        outlined style=" background-color: transparent; color: var(--danger); border: 1px solid;"
        :chooseLabel="filePasien ? filePasien.name : 'Unggah'" @select="onSelect($event)" class="is-rounded w-100" />
    </div>
    <template #footer>
      <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
        Batal
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
        @click="simpan()"> Simpan
      </VButton>
    </template>
  </Dialog>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import FileUpload from 'primevue/fileupload';
import BerkasPasienView from './berkas-pasien-preview.vue'
import { useUserSession } from '/@src/stores/userSession'

useHead({
  title: 'Rencana Keperawatan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
  }
)
const userLogin = useUserSession().getUser()
const filePasien: any = ref()
const modalInput: any = ref(false)
const isLoading: any = ref(false)

const input: any = ref({
  author: '',
})
const d_Berkas: any = ref([])
const filter: any = ref('')
const dataSource: any = ref([])
const filteredList = computed(() => {
  if (!filter.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.nama.match(new RegExp(filter.value, 'i')) ||
      items.deskripsi?.match(new RegExp(filter.value, 'i'))
    )
  })
})
console.table(userLogin)
const setAutoFill = async () => {
  input.value.author = userLogin.namaUser;
}

const loadRiwayat = () => {
  isLoading.value = true
  useApi().get(`/emr/berkas-pasien?nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}`).then((response: any) => {
    isLoading.value = false
    dataSource.value = response.data
  })
}
const loadDrop = () => {
  useApi().get(`/emr/combo-jenis-berkas`).then((response: any) => {
    d_Berkas.value = response
  })
}
const add = () => {
  filePasien.value = null
  input.value = {}
  input.value.author = userLogin.namaUser
  modalInput.value = true
}
const onSelect = async (filez: any) => {
  const file = filez.files[0];
  if (file.size > 10000000) {
    H.alert('error', 'Maksimal file size adalah 10 MB')
    return
  }
  filePasien.value = file
}
const kembaliKeun = () => {
  modalInput.value = false
  input.value = {
    tanggal: new Date()
  }
}
const simpan = async () => {
  if (!input.value.namafile) {
    H.alert('error', 'Jenis File harus di isi')
    return
  }
  if (!input.value.nama) {
    H.alert('error', 'Nama harus di isi')
    return
  }
  if (!filePasien.value) {
    H.alert('error', 'File harus di unggah')
    return
  }
  const formData = new FormData()
  formData.append('filePasien', filePasien.value)
  formData.append('norec', input.value.norec ? input.value.norec : '')
  formData.append('noregistrasi', props.registrasi.noregistrasi)
  formData.append('nocm', props.pasien.nocm)
  formData.append('norec_apd', props.registrasi.norec_apd)
  formData.append('namafile', input.value.namafile.nama)
  formData.append('keterangan', input.value.keterangan ? input.value.keterangan : null)
  formData.append('objectberkaspasien', input.value.namafile.id)
  formData.append('nama', input.value.nama)
  formData.append('author', input.value.author)
  isLoading.value = true
  await useApi().post('/emr/simpan-berkas-pasien', formData).then((r) => {
    isLoading.value = false
    loadRiwayat()
    modalInput.value = false
  }).catch((e: any) => {
    isLoading.value = false
  })

}
const edit = async (e: any) => {
  input.value.norec = e.norec
  input.value.keterangan = e.deskripsi
  input.value.nama = e.nama
  d_Berkas.value.forEach((element: any) => {
    if (e.objectberkaspasien == element.id) {
      input.value.namafile = element
    }
  });
  let path = 'berkaspasien/' + e.nocm + '/' + e.namafile
  let file = await H.getFileBE(path);
  filePasien.value = file
  filePasien.value.name = e.namafile
  modalInput.value = true
}
const hapus = async (e: any) => {
  e.loadingHapus = true
  await useApi().post(`/emr/hapus-berkas-pasien`, { 'norec': e.norec }).then((response: any) => {
    e.loadingHapus = false
    loadRiwayat()
  })
}
const lihat = async (e: any) => {
  H.openFile('berkaspasien/' + e.nocm + '/' + e.namafile);
}
loadDrop()
loadRiwayat()
onMounted(() => {
  setAutoFill();
});
</script>

<style lang="scss"></style>
