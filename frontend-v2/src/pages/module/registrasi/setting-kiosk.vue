<template>
    <TabView>
        <TabPanel header="Slotting">
            <div class="columns is-multiline">
                <div class="column is-3">
                    <VSnack :title="'Jumlah Loket Kiosk - ' + jumlahLoketSetting" color="primary" icon="lnil lnil-tab">
                    </VSnack>
                </div>
                <div class="column is-9">
                    <VButtons>
                        <VButton color="primary" icon="feather:send" raised rounded @click="LoketFormOpen = true"> Update
                            Jumlah</VButton>
                    </VButtons>
                </div>
            </div>
            <VCard class="py-4">
              <div class="columns is-multiline form-search">
                  <div class="column is-4">
                    <VField label="Filter Bedasarakan Tanggal">
                      <VDatePicker v-model="item.qFilterTglData" is-range color="pink" trim-weeks class="pt-2">
                        <template #default="{ inputValue, inputEvents }">
                          <VField addons>
                            <VControl icon="feather:calendar">
                              <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                            </VControl>
                            <VControl>
                              <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                            </VControl>
                            <VControl icon="feather:calendar">
                              <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                  <div class="column">
                    <VField class="is-autocomplete-select is-rounded" label="Nama Ruangan">
                      <VControl icon="feather:search">
                        <Multiselect mode="single" v-model="item.ruanganFilter" :options="listRuangan"
                          placeholder="Pilih data" :searchable="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column mt-5" style="margin-left: auto  !important;">
                    <VIconButton type="button" color="info" class="is-rounded" rounded raised icon="fas fa-search"
                      @click="loadCombo()" :loading="isLoading">
                    </VIconButton>
                  </div>
              </div>
            </VCard>
            <div class="columns is-multiline widget-demo-columns half-columns mt-3">
                <!--List Widget V1a-->
                <div class="column is-6" v-for="number in jumlahLoketSetting" :key="number">
                    <div class="list-widget is-straight">
                        <div class="widget-head">
                            <h3 class="dark-inverted">{{ 'KIOSK MESIN. ' + number }}</h3>
                            <VDropdown icon="feather:more-vertical" dots right spaced>
                                <template #content>
                                    <a href="#" class="dropdown-item is-media" @click="openFormSlotRuangan(number)">
                                        <div class="icon">
                                            <i aria-hidden="true" class="lnil lnil-circle-plus"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Tambah</span>
                                            <span>Tambah Slot Ruangan</span>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item is-media" @click="confirmDeleteSlotRuangan(number)">
                                        <div class="icon">
                                            <i aria-hidden="true" class="lnil lnil-trash-can-alt-1"></i>
                                        </div>
                                        <div class="meta">
                                            <span>Hapus</span>
                                            <span>Hapus Semua Slot Ruangan</span>
                                        </div>
                                    </a>
                                </template>
                            </VDropdown>
                        </div>

                        <div class="inner-list">
                            <div v-if="listSlottingKiosk[number]">
                              <!-- date here -->
                              <div class="column is-12" v-for="lokets in listSlottingKiosk[number]" :key="lokets.id">
                                <h1 style="font-weight: bold;" class="mb-4">{{ (lokets[0].tanggal && lokets[0].tanggal != null) ?  H.formatDateNoTime(lokets[0].tanggal) : "Tanggal Tidak Diketahui"}}</h1>
                               <div v-for="loket in lokets" :key="loket.id"
                                    class="inner-list-item media-flex-center px-2">
                                      <VAvatar :initials="loket.quota" size="medium"
                                          :color="avatarColors[parseInt(getRandomArbitrary(0, 10))]" />
                                      <div class="flex-meta is-light">
                                          <a href="#">{{ loket.namaruangan }}</a>
                                          <span>Waktu Pelayanan</span>
                                          <!-- <span>{{ loket.hari }}</span> -->
                                          <span>
                                              <VTag color="primary" class="mr-1" :label="loket.jambuka" />
                                              <VTag color="danger" :label="loket.jamtutup" />
                                          </span>
                                      </div>
                                      <div class="flex-end">
                                          <VDropdown icon="feather:more-vertical" dots right spaced>
                                              <template #content>
                                                  <a href="#" class="dropdown-item is-media"
                                                      @click="editSlotRuangan(loket, number)">
                                                      <div class="icon">
                                                          <i aria-hidden="true" class="lnil lnil-pencil-alt"></i>
                                                      </div>
                                                      <div class="meta">
                                                          <span>Edit</span>
                                                          <span>Edit Slot Ruangan</span>
                                                      </div>
                                                  </a>
                                                  <a href="#" class="dropdown-item is-media"
                                                      @click="deleteSlotRuangan(loket.id)">
                                                      <div class="icon">
                                                          <i aria-hidden="true" class="lnil lnil-trash-can-alt-1"></i>
                                                      </div>
                                                      <div class="meta">
                                                          <span>Hapus</span>
                                                          <span>Hapus Slot Ruangan</span>
                                                      </div>
                                                  </a>
                                              </template>
                                          </VDropdown>
                                    </div>
                                </div>
                              </div>
                              <!-- <h1 style="font-weight: bold;" class="mb-4">{{listSlottingKiosk[number]  }}</h1> -->

                            </div>
                            <div v-else>
                                <span>Tidak ada data.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </TabPanel>
        <!-- <TabPanel header="Map Dokter To Poli">
            <TabView v-model:activeIndex="activeIdx2">
                <TabPanel header="List">
                </TabPanel>
                 <TabPanel header="Mapping Data">
                </TabPanel>
            </TabView>
        </TabPanel> -->
        <!-- Aktifkan Buat Nanti -->
        <!-- <TabPanel header="Sisa Kouta">
            <div class="columns is-multiline"></div>
        </TabPanel>
        <TabPanel header="Setting Tampilan Kiosk">
            <div class="columns is-multiline"></div>
        </TabPanel> -->
        <!-- End Aktifkan Buat Nanti -->
    </TabView>

    <!-- Start modal  -->
    <VModal is="form" :open="LoketFormOpen" title="Jumlah Loket Kiosk" size="small" actions="right"
        @close="LoketFormOpen = false">
        <template #content>
            <div class="modal-form">
                <div class="field">
                    <label>Jumlah Loket *</label>
                    <div class="control">
                        <input type="text" class="input" placeholder="Jumlah Loket" v-model="item.jumlahLoket" />
                    </div>
                </div>
            </div>
        </template>
        <template #action>
            <VButton type="button" color="primary" @click="updateJumlahLoket" raised :loading="isLoading">Update</VButton>
        </template>
    </VModal>

    <VModal :open="slotRuanganFormOpen" :title="'Form Slot Ruangan - Loket Kiosk ' + item.activeLoket" size="medium"
        actions="right" @close="slotRuanganFormOpen = false">
        <template #content>
            <div class="modal-form">
                <div class="columns is-multiline">
                    <div class="column is-12">
                      <VField label="Tanggal *"  class="w-100" v-if="item.edit">
                          <VDatePicker v-model="item.start" mode="dateTime" style="width: 100%;" >
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </VField>
                        <VField label="Tanggal *"  class="w-100" v-else>
                        <VDatePicker v-model="item.qFilterTgl" is-range color="pink" class="w-100" trim-weeks >
                          <template #default="{ inputValue, inputEvents }">
                            <VField addons class="w-100" >
                              <VControl icon="feather:calendar">
                                <VInput :value="inputValue.start" class="input-calendar w-100" v-on="inputEvents.start" />
                              </VControl>
                              <VControl>
                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                              </VControl>
                              <VControl icon="feather:calendar">
                                <VInput :value="inputValue.end" class="input-calendar w-100" v-on="inputEvents.end"  />
                              </VControl>
                            </VField>
                          </template>
                        </VDatePicker>
                      </VField>
                    </div>
                    <div class="column is-12">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="item">Label Ruangan *</VLabel>
                            <VControl icon="feather:search" class="prime-auto-select">
                                <Dropdown v-model="item.ruangan" :options="listRuangan" :optionLabel="'label'"
                                    placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                            </VControl>
                        </VField>
                        <!-- <VField v-slot="{ id }" class="is-autocomplete-select" label="Ruangan *">
                            <VControl icon="feather:search">
                                <Multiselect v-model="item.ruangan" :attrs="{ id }" :options="listRuangan"
                                    placeholder="Pilih Ruangan" :searchable="true" />
                            </VControl>
                        </VField> -->
                    </div>
                    <div class="column is-6 mb-5">
                        <VDatePicker v-model="item.jambuka" color="green" mode="time" is24hr>
                            <template #default="{ inputValue, inputEvents }">
                                <VField label="Jam Buka">
                                    <VControl icon="feather:clock">
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-6">
                        <VDatePicker v-model="item.jamtutup" color="green" mode="time" is24hr>
                            <template #default="{ inputValue, inputEvents }">
                                <VField label="Jam Tutup">
                                    <VControl icon="feather:clock">
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>

                    <div class="column is-12">
                        <VField label="Kouta *">
                            <VControl>
                                <VInput type="text" placeholder="Kouta Poli" v-model="item.kouta" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
        </template>
        <template #action>
            <VButton color="primary" raised @click="simpanSlotRuangan()" :loading="isLoading">Simpan</VButton>
        </template>
    </VModal>
    <VModal :open="confirmHapusAll" size="small" title="Confirm Hapus Data" actions="center"
        @close="confirmHapusAll = false">
        <template #content>
            <VPlaceholderSection title="Peringatan" subtitle="Apakah Anda yakin ingin menghapus semua data ?" />
        </template>
        <template #action>
            <VButton color="primary" @click="deleteSlotAll()" raised :loading="isLoading">Confirm</VButton>
        </template>
    </VModal>
</template>
<script setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useRoute, useRouter } from 'vue-router'
import { todoList3, todoList4 } from '/@src/data/widgets/list/todoList'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'

import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import { useToaster } from '/@src/composable/toaster'
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';


// dummy
import { userList } from '/@src/data/widgets/list/userList'
import { datatableV3 } from '/@src/data/layouts/datatable-v3'
useHead({
    title: 'Setting Kiosk - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const listSlottingKiosk: any = ref({})
const listRuangan: any = ref([])
const d_Ruangan: any = ref([])
const jumlahLoketSetting = ref(0)
const date = new Date();
date.setDate(date.getDate() + 30);
const item: any = reactive({
    jumlahLoket: 0,
    activeLoket: 0,
    settingdatafixed: {},
    jambuka: "2023-10-25 00:00",
    jamtutup: "2023-10-25 00:00",
    qFilterTgl: {
      start: new Date(),
      end: new Date()
    },
    qFilterTglData :{
      start: new Date(),
      end: new Date(date)
    }
})
const listHari = [
    { value: 'SENIN', label: 'Senin' },
    { value: 'SELASA', label: 'Selasa' },
    { value: 'RABU', label: 'Rabu' },
    { value: 'KAMIS', label: 'Kamis' },
    { value: 'JUMAT', label: 'Jumat' },
    { value: 'SABTU', label: 'Sabtu' },
    { value: 'MINGGU', label: 'Minggu' },
]
const avatarColors: any = reactive([
    'primary',
    'success',
    'info',
    'warning',
    'danger',
    'h-purple',
    'h-orange',
    'h-blue',
    'h-green',
    'h-red',
    'h-yellow',
])
let isLoading: any = ref(false)
const LoketFormOpen = ref(false)
const slotRuanganFormOpen = ref(false)
const confirmHapusAll = ref(false)
function getRandomArbitrary(min:any, max:any) {
    return Math.random() * (max - min) + min;
}
const loadCombo = async () => {
    isLoading.value = true
    await useApi().get(`/sysadmin/get-settingdatafixed?namafield=jumlahLoketKiosk`).then((response: any) => {
        loadSlottingKiosk()
        isLoading.value = false
        item.settingdatafixed = response.data[0]
        jumlahLoketSetting.value = parseFloat(response.data[0].nilaifield)
        item.jumlahLoket = parseFloat(response.data[0].nilaifield)
    })

    await useApi().get(`/medifirst2000/kiosk/get-combo-kiosk2`).then((response: any) => {
        for (let i = 0; i < response.ruanganrajalnew.length; i++) {
            const element = response.ruanganrajalnew[i];
            listRuangan.value.push(
                {
                    value: element.id, label: element.namaruangan
                }
            )
        }
    })
}

const loadSlottingKiosk = async () => {

    const ruangan =item.ruanganFilter == undefined ? "" :item.ruanganFilter;
    const start   =H.formatDate(item.qFilterTglData.start,"YYYY-MM-DD");
    const end     =H.formatDate(item.qFilterTglData.end,"YYYY-MM-DD");
    await useApi().get(`/medifirst2000/kiosk/get-slotting-kiosk?objectrunaganfk=${ruangan}&start=${start}&end=${end}`).then((response: any) => {
        isLoading.value = false
        jumlahLoketSetting.value = parseFloat(item.jumlahLoket)
        listSlottingKiosk.value = response.dataperloket
    })
}
const updateJumlahLoket = async () => {
    if (!item.jumlahLoket) {
        useToaster().error('Jumlah Loket Harus diisi !')
        return
    }

    if (isNaN(item.jumlahLoket)) {
        useToaster().error('Harap isi jumlah dengan angka !')
        return
    }

    item.settingdatafixed.nilaifield = item.jumlahLoket
    var objSave = {
        'settingdatafixed': item.settingdatafixed
    }
    isLoading.value = true
    await useApi().post(`/sysadmin/post-settingdatafixe`, objSave).then((response: any) => {
        isLoading.value = false
        LoketFormOpen.value = false
        loadSlottingKiosk()
    }, (error) => {
        isLoading.value = false
    })
}
const openFormSlotRuangan = (number : any) => {
    slotRuanganFormOpen.value = true
    item.activeLoket = number
    clearForm()
}
const simpanSlotRuangan = async () => {
    if (!item.ruangan) {
        useToaster().error('Harap pilih ruangan terlebih dahulu !')
        return
    }
    if (!item.jambuka) {
        useToaster().error('Harap set jam buka terlebih dahulu !')
        return
    }
    if (!item.jamtutup) {
        useToaster().error('Harap set jam tutup terlebih dahulu !')
        return
    }
    if (!item.qFilterTgl) {
        useToaster().error('Harap pilih tanggal terlebih dahulu !')
        return
    }
    if (!item.kouta) {
        useToaster().error('Harap isi kouta terlebih dahulu !')
        return
    }
    if (isNaN(item.kouta)) {
        useToaster().error('Harap isi kouta dengan angka !')
        return
    }

    // let listHari = '';
    // for (var i = 0; i < item.hari.length; i++) {
    //     if (i == item.hari.length - 1) {
    //         listHari += item.hari[i].value
    //     } else {
    //         listHari += item.hari[i].value + ", "
    //     }
    // }

    const objSave = {
        "id": item.idslot ? item.idslot : '',
        "loketid": item.activeLoket,
        "objectruanganfk": item.ruangan.value,
        "jambuka": H.formatDate(item.jambuka, 'HH:mm'),
        "jamtutup": H.formatDate(item.jamtutup, 'HH:mm'),
        "start" :item.edit ?  H.formatDate(item.start,"YYYY-MM-DD")  : H.formatDate(item.qFilterTgl.start,"YYYY-MM-DD"),
        "end" : item.edit ?  H.formatDate(item.start,"YYYY-MM-DD")  : H.formatDate(item.qFilterTgl.end,"YYYY-MM-DD"),
        "quota": item.kouta,
    }
    isLoading.value = true
      await useApi().post(`/medifirst2000/kiosk/save-slotting-kiosk`, objSave).then((response: any) => {
          isLoading.value = false
          slotRuanganFormOpen.value = false
          loadSlottingKiosk()
      }, (error) => {
          isLoading.value = false
    })

}
const editSlotRuangan = async (data:any, loket:any) => {
    // set data
    // let dataHari = []
    // let dataHariSelect = data.hari.split(",")
    // for (let i = 0; i < dataHariSelect.length; i++) {
    //     const element = dataHariSelect[i];
    //     for (let j = 0; j < listHari.length; j++) {
    //         const element2 = listHari[j];
    //         if(element.trim() == element2.value) {
    //             dataHari.push(element2)
    //         }
    //     }
    // }
    clearForm()
    listRuangan.value.push({ value: data.idruangan, label: data.namaruangan })
    item.idslot = data.id
    item.edit =true;
    item.ruangan = { value: data.idruangan, label: data.namaruangan }
    item.jambuka = H.formatDate(new Date(), 'YYYY-MM-DD') + " " + data.jambuka
    item.jamtutup = H.formatDate(new Date(), 'YYYY-MM-DD') + " " + data.jamtutup
    item.kouta = data.quota
    item.start =new Date(data.tanggal);
    slotRuanganFormOpen.value = true
    item.activeLoket = loket
}
const deleteSlotRuangan = async (id : any) => {
    const objDelete = {
        "id": id,
    }
    isLoading.value = true
    await useApi().post(`/medifirst2000/kiosk/delete-slotting-kiosk`, objDelete).then((response: any) => {
        isLoading.value = false
        loadSlottingKiosk()
    }, (error) => {
        isLoading.value = false
    })
}
const confirmDeleteSlotRuangan = (number :any) => {
    confirmHapusAll.value = true
    item.activeLoket = number
}
const deleteSlotAll = async () => {
    const objDelete = {
        "loketid": item.activeLoket,
    }
    isLoading.value = true
    await useApi().post(`/medifirst2000/kiosk/delete-slotting-kiosk`, objDelete).then((response: any) => {
        isLoading.value = false
        confirmHapusAll.value = false
        loadSlottingKiosk()
    }, (error) => {
        isLoading.value = false
    })
}
const clearForm = () => {
    item.jambuka = "2023-10-25 00:00"
    item.jamtutup = "2023-10-25 00:00"
    delete item.ruangan
    delete item.hari
    delete item.kouta
    delete item.idslot
}

loadCombo()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/registrasi/setting-kiosk.scss';

.has-top-nav {

    .flex-list-wrapper,
    .list-flex-toolbar {
        max-width: 880px;
        margin-right: auto;
        margin-left: auto;
    }
}

.snack {
    display: inline-block;
    background: var(--fade-grey-light-2);
    height: 38px;
    width: 100%;
    border-radius: 500px;
    border: 1px solid var(--fade-grey-dark-3);
    transition: all 0.3s;
}

.snack .snack-text {
    top: -10px;
    width: auto !important;
    white-space: nowrap;
    overflow: hidden !important;
    text-overflow: ellipsis;
}

.media-flex-center .flex-meta span:not(:first-child),
.media-flex-center .flex-meta>a:not(:first-child) {
    font-family: var(--font);
    color: var(--light-text);
    font-size: 0.9rem;
}

.list-widget {
    @include vuero-l-card;

    padding: 30px;

    &:not(:last-child) {
        margin-bottom: 1.5rem;
    }

    &.is-straight {
        @include vuero-s-card;
    }

    .widget-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 32px;
        margin-bottom: 10px;

        h3 {
            color: var(--dark-text);
            font-size: 1.1rem;
            font-weight: 500;
        }
    }

    .inner-list {
        padding: 10px 0;

        .inner-list-item {
            +.inner-list-item {
                margin-top: 24px;
            }
        }
    }

    .go-icon {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 36px;
        width: 36px;
        border-radius: var(--radius-rounded);
        background: var(--widget-grey);
        color: var(--light-text);

        &::before {
            content: attr(data-content);
            position: absolute;
            left: -45px;
            font-family: var(--font);
            font-size: 0.95rem;
            font-weight: 500;
        }

        &.is-squared {
            border-radius: 10px;
        }

        &.is-up {
            &::before {
                color: var(--green);
            }
        }

        &.is-down {
            &::before {
                color: var(--red);
            }
        }

        svg {
            height: 16px;
            width: 16px;
            stroke-width: 3px;
        }
    }
}

.is-dark {
    .list-widget {
        @include vuero-card--dark;

        .go-icon {
            background: var(--dark-sidebar-light-3);
            border: 1px solid var(--dark-sidebar-light-12);
        }
    }
}
.form-search{
  display: flex !important;
  align-items: center;
}
</style>
