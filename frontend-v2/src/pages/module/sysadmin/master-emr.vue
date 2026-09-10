<template>
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">EMR</h3>
    </div>
    <div class="columns is-multiline">
      <div class="column is-12">
        <div class="user-grid user-grid-v2">
          <div class="columns is-multiline ">
            <div class="column is-12 ">
              <img src="/images/avatars/label/asal-anggaran.png" alt="" srcset=""
                style="max-width: 25%;margin-left: 75%;margin-top: -5rem;z-index: 2;position: sticky;">
              <div class="card" style="margin-top: -6rem;">
                <div class="columns is-multiline p-3">
                  <div class="column is-2 ">
                    <VButton @click="add()" type="button" icon="feather:plus" class="is-fullwidth" color="success"
                      raised>
                      Tambah
                    </VButton>
                  </div>
                  <div class="column is-2 ">
                    <VButton @click="expandAll()" outlined type="button" icon="feather:plus" class="is-fullwidth"
                      color="warning" raised>
                      Expand
                    </VButton>
                  </div>
                  <div class="column is-2 ">
                    <VButton @click="collapseAll()" outlined type="button" icon="feather:minus" class="is-fullwidth"
                      color="warning" raised>
                      Collapse
                    </VButton>
                  </div>
                  <div class="column is-12 ">
                    <Tree v-model:expandedKeys="expandedKeys" v-model:selectionKeys="selectedKey" :value="nodes"
                      class="w-full md:w-30rem" :filter="true" filterMode="lenient" @nodeSelect="onNodeSelect"
                      selectionMode="single" :metaKeySelection="false"></Tree>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </VCard>
  <Dialog v-model:visible="modalDetail" modal :header="input.id ? 'Ubah ' + input.label : 'Tambah'"
    :style="{ width: '30vw' }">
    <div class="columns is-multiline">
      <div class="column is-12">
        <VField label="Jenis EMR">
          <Multiselect v-model="input.jenisEMR" :attrs="{ value }" label="label" :options="d_JenisEMR"
            :searchable="true" track-by="label" mode="single" autocomplete="off" class="is-rounded">
          </Multiselect>
          <!-- <input v-model="input.jenisEMR" type="text" class="input is-rounded" placeholder="Jenis EMR" /> -->
        </VField>
      </div>
      <div class="column is-12 pt-0 pb-0" v-if="input.jenisEMR != 'bundle' && input.jenisEMR != 'navigasi'">
        <VField label="Head" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
          <VControl icon="fas fa-archway" fullwidth class="prime-auto-select">
            <Dropdown v-model="input.headfk" :options="d_EMR" :optionLabel="'label'" class="is-rounded"
              placeholder="Head" style="width: 100%;" :filter="true" showClear @change="changeHead($event)">
              <template #value="slotProps">
                <div v-if="slotProps.value" class="flex align-items-center">
                  <div>{{ slotProps.value.label }}</div>
                </div>
                <span v-else>
                  {{ slotProps.value }}
                </span>
              </template>
              <template #option="slotProps">
                <div :style="'font-weight:' + (slotProps.option.headfk ? 'normal' : 'bold')">
                  {{ slotProps.option.label }}
                </div>
              </template>
            </Dropdown>
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField label="Caption">
          <VControl icon="feather:bookmark">
            <input v-model="input.caption" type="text" class="input is-rounded" placeholder="Caption" />
          </VControl>
        </VField>
      </div>
      <div class="column is-12" v-if="input.jenisEMR != 'bundle' && input.jenisEMR != 'navigasi'">
        <VField label="Url Form">
          <VControl icon="feather:external-link">
            <input v-model="input.url_form" type="text" class="input is-rounded" placeholder="Url Form" />
          </VControl>
        </VField>
      </div>
      <div class="column is-12" v-if="input.jenisEMR != 'bundle' && input.jenisEMR != 'navigasi'">
        <VField label="Collection Table">
          <VControl icon="feather:database">
            <input v-model="input.collection" type="text" class="input is-rounded" placeholder="Collection Table" />
          </VControl>
        </VField>
      </div>
      <div class="column is-12" v-if="input.jenisEMR != 'bundle' && input.jenisEMR != 'navigasi'">
        <VField label="No Urut">
          <VControl icon="feather:bookmark">
            <input v-model="input.nourut" type="number" class="input is-rounded" placeholder="No Urut" />
          </VControl>
        </VField>
      </div>
      <div class="column is-12" v-if="input.jenisEMR != 'bundle' && input.jenisEMR != 'navigasi'">
        <VField label="Head Bundling">
          <MultiSelect v-model="input.headbundlingfk" display="chip" :options="d_HeadBundlingFK" optionLabel="name"
            filter :maxSelectedLabels="3" style="display:flex" class="is-rounded" />
          <!-- <VControl icon="feather:file">
            <input v-model="input.headbundlingfk" type="text" class="input is-rounded"
              placeholder="Jika ingin mapping lebih dari satu, gunakan format 1,2,3,4..." />
          </VControl> -->
        </VField>
      </div>
    </div>
    <template #footer>
      <VButton v-if="input.id" type="button" rounded outlined color="danger" raised icon="feather:trash"
        :loading="isLoading" @click="deleteItem(input)"> Hapus
      </VButton>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="tutup()">
        Tutup
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
        @click="simpan()"> {{ input.id ? 'Ubah' : 'Simpan' }}
      </VButton>
    </template>
  </Dialog>

  <Toast position="bottom-right" />
</template>

<script setup lang="ts">
import { useToast } from "primevue/usetoast";
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable'
import FileUpload from 'primevue/fileupload';
import TandaTangan from '../emr/profile-pasien/page-emr-plugins/tanda-tangan.vue'
import Dialog from 'primevue/dialog'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import Dropdown from 'primevue/dropdown';
import Tree from 'primevue/tree';
import MultiSelect from 'primevue/multiselect';

useHead({
  title: 'Master EMR - ' + import.meta.env.VITE_PROJECT,
})

const toast = useToast();
const expandedKeys: any = ref({});
const selectedKey = ref(null);
let d_HeadBundlingFK = ref([])
let d_Pegawai = ref([])
let d_EMR: any = ref([])
let namaPegawai = ref('')
const confirm = useConfirm()
const nodes: any = ref(null);
const selectView: any = ref()
const input: any = ref({
  headbundlingfk: {}
})
let isLoading: any = ref(false)
let modalDetail: any = ref(false)

const filters = ref('')
const route = useRoute()

const d_JenisEMR = ref([
  { value: 'asesmen', label: "Asesmen" },
  { value: 'navigasi', label: "Navigasi" },
  { value: 'bundle', label: "Bundle" }
]);

const expandAll = () => {
  for (let node of nodes.value) {
    expandNode(node);
  }

  expandedKeys.value = { ...expandedKeys.value };
};

const collapseAll = () => {
  expandedKeys.value = {};
};
const expandNode = (node: any) => {
  if (node.children && node.children.length) {
    expandedKeys.value[node.key] = true;

    for (let child of node.children) {
      expandNode(child);
    }
  }
};

const recursiveMap = (arr: any, callback: any) => {
  return arr.map((item: any) => {
    if (item.child && item.child.length > 0) {
      // If the item has children, recursively call the function on the children array.
      item.children = recursiveMap(item.child, callback);
    }
    // Apply the callback function to the current object.
    return callback(item);
  });
}

// Create a callback function that adds a "mapped" property.
const addMappedProperty = (obj: any) => {
  return { ...obj };
}
const add = async () => {
  clear()
  input.value.jenisEMR = 'asesmen'

  await useApi().get(
    `/sysadmin/master-emr/nourut`
  ).then((response) => {
    input.value.nourut = response
  })
  modalDetail.value = true
}

const fetchData = async () => {
  isLoading.value = true
  await useApi().get(`/sysadmin/master-emr?namaemr=asesmen`).then((response: any) => {
    nodes.value = recursiveMap(response.data, addMappedProperty);

    isLoading.value = false
  }).catch((err) => {
    isLoading.value = false
  })
}

const fetchDataHeadBundling = async () => {
  isLoading.value = true
  useApi().get(`/emr/menu-emr-bundle`).then((response: any) => {
    d_HeadBundlingFK.value = response
    isLoading.value = false
  }).catch((err) => {
    isLoading.value = false
  })
}
const onNodeSelect = async (node: any) => {
  clear()
  modalDetail.value = true

  input.value.id = node.key
  input.value.label = node.label
  if (node.headfk) {

    d_EMR.value.forEach(element => {
      if (element.value == node.headfk) {
        input.value.headfk = element
      }
    });
  }
  input.value.jenisEMR = 'asesmen'
  input.value.caption = node.label
  input.value.nourut = node.nourut
  input.value.collection = node.collection
  input.value.url_form = node.url_form
  let headBundling =  node.headbundlingfk != null ? node.headbundlingfk.split(',') : '';
  await useApi().get(`/emr/menu-emr-bundle?headBundling=${headBundling}&getHead=true`).then((response: any) => {
    input.value.headbundlingfk = response != null ? response : {}
  })
  toast.add({ severity: 'success', summary: 'Selected', detail: node.label, life: 3000 });
};
const changeHead = async (node) => {
  await useApi().get(
    `/sysadmin/master-emr/nourut?id=${node.value.value}`
  ).then((response) => {
    input.value.nourut = response
  })
}
const simpan = async () => {
  let text = null
  if (!input.value.caption) {
    H.alert('error', 'Caption EMR tidak boleh kosong')
    return
  }
  // if (!input.value.collection) {
  //   H.alert('error', 'Collection tidak boleh kosong')
  //   return
  // }
  if (!input.value.nourut) {
    H.alert('error', 'No Urut tidak boleh kosong')
    return
  }
  // if (!input.value.url_form) {
  //   H.alert('error', 'URL Form tidak boleh kosong')
  //   return
  // }

  if (input.value.headbundlingfk != null) {
    text = input.value.headbundlingfk.map(item => item.id).join(',');
  }

  let objSave = {
    "id": input.value.id ? input.value.id : "",
    "caption": input.value.caption,
    "nourut": input.value.nourut,
    "url_form": input.value.url_form ? input.value.url_form : null,
    "collection": input.value.collection ? input.value.collection : null,
    "headfk": input.value.headfk ? input.value.headfk.value : null,
    "headbundlingfk": text != null ? text : null,
    "namaemr": input.value.jenisEMR ? input.value.jenisEMR : 'asesmen',
  }

  isLoading.value = true
  await useApi().post('sysadmin/master-emr/save', objSave).then((response: any) => {
    isLoading.value = false

    clear()
    fetchData()
    fetchEMR()
    fetchDataHeadBundling()
    modalDetail.value = false
  }, (error) => {
    isLoading.value = false
  })
}

const fetchEMR = async () => {
  await useApi().get(
    `emr/dropdown/emr_t?select=id,caption,url_form,headfk,nourut,collection&param_search=caption&query=&limit=1000`
  ).then((response) => {
    d_EMR.value = response.map((e: any) => {
      return { label: e.caption, value: e.id, headfk: e.headfk }
    })
  })
}

const deleteItem = async (e: any) => {
  await useApi().post(`sysadmin/master-emr/delete`, { 'id': e.id }).then((response: any) => {
    fetchData()
    fetchDataHeadBundling()
    fetchEMR()
    modalDetail.value = false
  }, (error) => {

  })
}
const edit = async (e: any) => {
  input.value.id = e.id
  d_Pegawai.value.forEach((element: any) => {
    if (element.value == e.pegawaifk) {
      input.value.pegawaifk = element
    }
  });

}
const clear = () => {
  input.value = {}
}
const tutup = () => {
  clear()
  modalDetail.value = false
}

fetchEMR()
fetchData()
fetchDataHeadBundling()



</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.masterTTD {
  .button.is-danger.is-outlined {
    display: none;
  }
}
</style>
