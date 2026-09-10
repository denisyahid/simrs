<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <div class="py-4">
        <h1 style="font-weight:bold">Waktu Tunggu</h1>
      </div>
      <div class="column is-12">
        <VCard>
          <div class="columns is-multiline py-2">
            <div class="column is-6">
              <VField class="is-rounded-select is-autocomplete-select" label="Kode Booking">
                <VControl icon="feather:search" class="prime-auto-select">
                  <VInput v-model="input.search" placeholder="Cari kode booking" class="is-rounded"></VInput>
                </VControl>
              </VField>
            </div>
            <div class="column  mt-5">
              <VButton type="button" color="info" class="searcv-button mr-2" raised icon="fas fa-search"
                @click="fetchData()" :loading="isLoading">Cari
              </VButton>
              <VButton type="button" color="success" class="searcv-button" raised icon="fas fa-save"
                @click="showModalUpdateWaktu()" :loading="isLoadingSave">Update Waktu
              </VButton>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12 px-4">
        <VCard>
          <div class="flex-list-inner mb-2 mt-5" v-if="isLoading">
            <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
              <VPlaceloadWrap>
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
          </div>
          <div class="flex-list-inner" v-else-if="!dataSourcefiltered.data">
            <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
              <template #image>
                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderSection>
          </div>
          <div v-else>
            <div class="tile-grid tile-grid-v3">
              <div class="tile is-ancestor" v-for="(item, i) in dataSourcefiltered.data">
                <div class="tile is-parent">
                  <a class="tile is-child tile-grid-item is-medium">
                    <div class="tile-grid-item-inner">
                      <div class="meta">
                        <div class="tile-title">
                          <p>
                            {{ item.taskname }} / ({{ item.taskid }})
                          </p>
                        </div>
                        <div class="tile-meta">
                          <VAvatar size="small" :initials="i + 1" :color="item.color" />
                          <div class="meta-inner">
                            <span class="dark-inverted" data-filter-match>{{ item.kodebooking
                            }}</span>
                            <span><a style="color: var(--light-text);">{{ item.wakturs }} / {{ item.waktu }} </a></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </VCard>
      </div>
    </div>
  </div>
  <!-- modal update waktu booking -->
  <VModal :open="modalInputUpdateWaktu" title="Update Waktu" :noclose="false" size="medium" actions="right"
    @close="modalInputUpdateWaktu = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField label="Kode Booking">
              <VControl icon="feather:list">
                <VInput class="is-rounded" placeholder="Masukan kode booking" v-model="input.kodebookingUpdate"></VInput>
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField class="is-rounded-select is-autocomplete-select" label="Jenis Waktu">
              <VControl icon="feather:search" class="prime-auto-select" :loading="isLoadingSelect">
                <Dropdown v-model="input.jenisWaktu" :options="d_JenisWaktu" :optionLabel="'label'"
                  placeholder="Pilih data" style="width: 100%;" showClear :filter="true" class="is-rounded" />
              </VControl>
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="simpan()" :loading="isLoading" color="primary" raised>
        Simpan
      </VButton>
    </template>
  </VModal>
  <!-- end modal update waktu booking -->
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';
const setView = () => {
  useHead({
    title: 'Waktu Tunggu - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const isLoadingCall = ref(false)
const input: any = ref({})
let item: any = reactive({
  pencarian: 'nobpjs'
})
const dataSourcefiltered: any = ref([]);
const columns: any = ref({})
const d_DokterBPJS: any = ref([])
const d_Dokter: any = ref([])
const route = useRoute()
const dataSource: any = ref([])
const isLoading: any = ref(false)
const isLoadingSave: any = ref(false)
const modalInputUpdateWaktu: boolean = ref(false);
const listColor: any = ref(Object.keys(useThemeColors()))
const d_JenisWaktu = [
  { value: 2, label: 'Waktu tunggu admisi' },
  { value: 2, label: 'Waktu layan admisi' },
  { value: 3, label: 'Waktu tunggu poli' },
  { value: 4, label: 'Waktu layan poli' },
  { value: 5, label: 'Waktu tunggu farmasi' },
  { value: 6, label: 'Waktu layan farmasi' },
  { value: 7, label: 'Waktu obat selesai dibuat' },
  { value: 99, label: 'Tidak hadir/batal' }
]
const showModalUpdateWaktu = () => {
  modalInputUpdateWaktu.value = true
}
const simpan = async () => {
  if (!input.value.kodebookingUpdate) {
    H.alert('warning', 'Data dokter rumah sakit harus diisi !');
    return;
  }
  if (!input.value.jenisWaktu) {
    H.alert('warning', 'Data dokter bpjs harus diisi !');
    return;
  }
  var obj = {
    "url": "antrean/updatewaktu",
    "jenis": "antrean",
    "method": "POST",
    "data": {
      "kodebooking": input.value.kodebookingUpdate,
      "taskid": input.value.jenisWaktu.value,
      "waktu": new Date().getTime()
    }

  }
  isLoadingSave.value = true;
  const data = useApi().postBPJS('/bridging/bpjs/tools', obj)
    .then((data: any) => {
      if (data.metaData.code == 200) {
        input.value.search = obj.data.kodebooking;
        H.alert('success', data.metaData.message);
      } else {
        H.alert('error', data.metaData.message)
      }
      clear();
    })
  isLoadingSave.value = false;
  modalInputUpdateWaktu.value = false
}
const fetchData = async () => {
  if (!input.value.search) {
    H.alert('warning', 'Kode booking harus diisi  !');
    return;
  }
  isLoading.value = true
  let obj = {
    "url": "antrean/getlisttask",
    "jenis": "antrean",
    "method": "POST",
    "data": {
      "kodebooking": input.value.search
    }
  }
  const data = useApi().postBPJS('/bridging/bpjs/tools', obj).then((data: any) => {
    if (data == '') {
      H.alert('error', 'Data Tidak Ditemukan')
      isLoading.value = false
    } else if (data.metaData.code == 200) {
      let x = 0
      for (let i = 0; i < data.response.length; i++) {
        const element = data.response[i];
        if (x > 5) {
          x = 0
        }
        element.color = listColor.value[x]
        x++
      }
      dataSourcefiltered.value.data = data.response
      H.alert('success', data.metaData.message)
      isLoading.value = false
    }
    else {
      H.alert('error', data.metaData.message)
      isLoading.value = false
    }
  });
}
const clear = () => {
  input.value.kodebookingUpdate = null;
  input.value.jenisWaktu = null;
}
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 8px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
  margin-top: 14px;
}

.tile-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }
}

.tile-grid-v3 {
  .tile {
    &.is-ancestor {
      margin-left: -0.5rem;
      margin-right: -0.5rem;
      margin-top: -0.5rem;
    }

    &.is-parent {
      padding: 0.5rem;
    }
  }

  .tile-grid-item {
    @include vuero-s-card;

    padding: 10px;
    border-radius: 16px;

    &.is-medium {
      max-height: 132px;

      .tile-grid-item-inner {
        display: flex;
        height: 100%;

        >img {
          display: block;
          border-radius: 12px;
          width: 100%;
          max-width: 110px;
          min-width: 110px;
          height: 100%;
          min-height: 110px;
          max-height: 110px;
          object-fit: cover;
        }

        .meta {
          margin-left: 12px;
          display: flex;
          flex-direction: column;
          justify-content: space-between;

          .tile-title {
            h3 {
              font-family: var(--font);
              font-family: var(--font-alt);
              font-size: 1rem;
              font-weight: 600;
              color: var(--dark-text);
              line-height: 1.3;
            }
          }

          .tile-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding: 0 0 5px;

            .v-avatar {
              max-width: 26px;
              max-height: 26px;
              min-width: 26px;

              .avatar {
                max-width: 26px;
                max-height: 26px;
                min-width: 26px;
              }
            }

            .meta-inner {
              margin-left: 8px;
              line-height: 1.2;

              span {
                display: block;
                font-weight: 400;

                &:first-child {
                  color: var(--dark-text);
                  font-family: var(--font-alt);
                  font-size: 0.85rem;
                  font-weight: 600;
                }

                &:nth-child(2) {
                  font-size: 0.8rem;
                  font-family: var(--font);
                  color: var(--light-text);
                }
              }
            }
          }
        }
      }
    }

    &.is-large {
      .tile-grid-item-inner {
        display: flex;
        flex-direction: column;
        height: 100%;

        >img {
          display: block;
          border-radius: 12px;
          width: 100%;
          height: 180px;
          object-fit: cover;
        }

        .meta {
          display: flex;
          flex-direction: column;
          justify-content: space-between;
          flex: 1 1 0;
          padding: 0 5px;

          .tile-title {
            padding-top: 10px;

            h3 {
              font-family: var(--font);
              font-size: 1.2rem;
              font-weight: 500;
              color: var(--dark-text);
              line-height: 1.1;
            }
          }

          .tile-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding: 0 0 5px;

            .v-avatar {
              max-width: 26px;
              max-height: 26px;
              min-width: 26px;

              .avatar {
                max-width: 26px;
                max-height: 26px;
                min-width: 26px;
              }
            }

            .meta-inner {
              margin-left: 8px;
              line-height: 1.2;

              span {
                display: block;
                font-weight: 400;

                &:first-child {
                  color: var(--dark-text);
                  font-size: 0.9rem;
                  font-weight: 500;
                }

                &:nth-child(2) {
                  font-size: 0.8rem;
                  color: var(--light-text);
                }
              }
            }
          }
        }
      }
    }

    &.is-wide {
      .tile-grid-item-inner {
        display: flex;
        height: 100%;

        >img {
          display: block;
          border-radius: 12px;
          width: 100%;
          max-width: 280px;
          height: 100%;
          min-height: 160px;
          object-fit: cover;
        }

        .meta {
          margin-left: 12px;
          display: flex;
          flex-direction: column;
          justify-content: space-between;

          .tile-title {
            padding-top: 5px;

            h3 {
              font-family: var(--font);
              font-size: 1.3rem;
              font-weight: 500;
              color: var(--dark-text);
              line-height: 1.1;
            }

            p {
              color: var(--light-text);
              font-size: 0.95rem;
              padding-top: 5px;
            }
          }

          .tile-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding: 0 0 5px;

            .v-avatar {
              max-width: 26px;
              max-height: 26px;
              min-width: 26px;

              .avatar {
                max-width: 26px;
                max-height: 26px;
                min-width: 26px;
              }
            }

            .meta-inner {
              margin-left: 8px;
              line-height: 1.2;

              span {
                display: block;
                font-weight: 400;

                &:first-child {
                  color: var(--dark-text);
                  font-size: 0.9rem;
                  font-weight: 500;
                }

                &:nth-child(2) {
                  font-size: 0.8rem;
                  color: var(--light-text);
                }
              }
            }
          }
        }
      }
    }

    &.is-tall {
      .tile-grid-item-inner {
        display: flex;
        flex-direction: column;
        height: 100%;

        >img {
          display: block;
          border-radius: 12px;
          width: 100%;

          // max-width: 110px;
          height: 220px;
          object-fit: cover;
        }

        .meta {
          display: flex;
          flex-direction: column;
          justify-content: space-between;
          flex: 1 1 0;
          padding: 0 5px;

          .tile-title {
            padding-top: 10px;

            h3 {
              font-family: var(--font);
              font-size: 1.2rem;
              font-weight: 500;
              color: var(--dark-text);
              line-height: 1.1;
            }

            p {
              color: var(--light-text);
              font-size: 0.95rem;
              padding-top: 5px;
            }
          }

          .tile-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding: 0 0 5px;

            .v-avatar {
              max-width: 26px;
              max-height: 26px;
              min-width: 26px;

              .avatar {
                max-width: 26px;
                max-height: 26px;
                min-width: 26px;
              }
            }

            .meta-inner {
              margin-left: 8px;
              line-height: 1.2;

              span {
                display: block;
                font-weight: 400;

                &:first-child {
                  color: var(--dark-text);
                  font-size: 0.9rem;
                  font-weight: 500;
                }

                &:nth-child(2) {
                  font-size: 0.8rem;
                  color: var(--light-text);
                }
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }

  .tile-grid-v3 {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }
}

@media only screen and (max-width: 767px) {
  .tile-grid-v3 {
    .tile-grid-item {
      max-height: 132px !important;

      .tile-grid-item-inner {
        display: flex !important;
        flex-direction: row !important;
        height: 100% !important;

        >img {
          display: block !important;
          border-radius: 12px !important;
          width: 100% !important;
          max-width: 110px !important;
          min-width: 110px !important;
          height: 100% !important;
          min-height: 110px !important;
          max-height: 110px !important;
          object-fit: cover !important;
        }

        .meta {
          margin-left: 12px !important;
          display: flex !important;
          flex-direction: column !important;
          justify-content: space-between !important;

          .tile-title {
            h3 {
              font-family: var(--font) !important;
              font-size: 1.1rem !important;
              font-weight: 500;
              color: var(--dark-text) !important;
              line-height: 1.1 !important;
            }

            p {
              display: none !important;
            }
          }

          .tile-meta {
            display: flex !important;
            align-items: center !important;
            margin-top: auto !important;
            padding: 0 0 5px !important;

            .v-avatar {
              max-width: 26px !important;
              max-height: 26px !important;
              min-width: 26px !important;

              .avatar {
                max-width: 26px !important;
                max-height: 26px !important;
                min-width: 26px !important;
              }
            }

            .meta-inner {
              margin-left: 8px !important;
              line-height: 1.2 !important;

              span {
                display: block !important;
                font-weight: 400 !important;

                &:first-child {
                  color: var(--dark-text) !important;
                  font-size: 0.9rem !important;
                  font-weight: 500 !important;
                }

                &:nth-child(2) {
                  font-size: 0.8rem !important;
                  color: var(--light-text) !important;
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .tile-grid-v3 {
    .tile-grid-item {
      max-height: 132px !important;

      .tile-grid-item-inner {
        display: flex !important;
        flex-direction: row !important;
        height: 100% !important;

        >img {
          display: block !important;
          border-radius: 12px !important;
          width: 100% !important;
          max-width: 110px !important;
          min-width: 110px !important;
          height: 100% !important;
          min-height: 110px !important;
          max-height: 110px !important;
          object-fit: cover !important;
        }

        .meta {
          margin-left: 12px !important;
          display: flex !important;
          flex-direction: column !important;
          justify-content: space-between !important;

          .tile-title {
            h3 {
              font-family: var(--font) !important;
              font-size: 1.1rem !important;
              font-weight: 500;
              color: var(--dark-text) !important;
              line-height: 1.1 !important;
            }

            p {
              display: block;
              max-width: 460px;
            }
          }

          .tile-meta {
            display: flex !important;
            align-items: center !important;
            margin-top: auto !important;
            padding: 0 0 5px !important;

            .v-avatar {
              max-width: 26px !important;
              max-height: 26px !important;
              min-width: 26px !important;

              .avatar {
                max-width: 26px !important;
                max-height: 26px !important;
                min-width: 26px !important;
              }
            }

            .meta-inner {
              margin-left: 8px !important;
              line-height: 1.2 !important;

              span {
                display: block !important;
                font-weight: 400 !important;

                &:first-child {
                  color: var(--dark-text) !important;
                  font-size: 0.9rem !important;
                  font-weight: 500 !important;
                }

                &:nth-child(2) {
                  font-size: 0.8rem !important;
                  color: var(--light-text) !important;
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .tile-grid-v3 {
    .tile-grid-item {
      &.is-medium {
        .tile-grid-item-inner {
          .tile-title {
            h3 {
              font-size: 1rem !important;
            }
          }
        }
      }

      &.is-large {
        .tile-grid-item-inner {
          .tile-title {
            h3 {
              font-size: 1.1rem !important;
            }
          }
        }
      }

      &.is-tall {
        .tile-grid-item-inner {
          .tile-title {
            h3 {
              font-size: 1.2rem !important;
            }
          }
        }
      }

      &.is-wide {
        .tile-grid-item-inner {
          >img {
            max-width: 180px;
            min-height: 160px;
          }

          .tile-title {
            h3 {
              font-size: 1.2rem !important;
            }
          }
        }
      }
    }
  }
}
</style>
