<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status primary">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">KODE</span>
              </div>
              <VPlaceload width="100%" height="10px" class="mx-1 mt-2" v-if="isLoading" />
              <small class="text-bold-custom h-100 pt-2" v-else>{{ item.rekanan }}</small>
            </VCardCustom>
          </div>
          <div class="column is-4">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status info">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">NAMA</span>
              </div>
              <VPlaceload width="100%" height="10px" class="mx-1 mt-2" v-if="isLoading" />
              <small class="text-bold-custom h-100 pt-2" v-else>{{ item.namaperusahaan }}</small>
            </VCardCustom>
          </div>
          <div class="column is-4">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status danger">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">PIUTANG</span>
              </div>
              <small class="text-bold-custom h-100 pt-2">{{
                H.formatRp(item.piutang, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0 pt-5">
        <div class="columns is-multiline">
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-8">
                <VCard>
                  <div class="column c-title pt-2 mb-5">
                    <label style="font-size:15px;font-weight:bold">Daftar Tagihan</label>
                  </div>
                  <div class="column">
                    <div class="flex-list-inner mb-4" v-if="isLoading">
                      <div class="flex-table-item grid-item mb-4" v-for="key in 2" :key="key">
                        <VFlexTableCell :column="{ grow: true, media: true }">
                          <VPlaceloadAvatar size="medium" />
                          <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                        </VFlexTableCell>
                        <VFlexTableCell>
                          <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                        </VFlexTableCell>
                        <VFlexTableCell>
                          <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                        </VFlexTableCell>
                        <VFlexTableCell :column="{ align: 'end' }">
                          <VPlaceload width="10%" class="mx-1" />
                        </VFlexTableCell>
                      </div>
                    </div>
                    <div class="flex-list-inner" v-else-if="dataPiutang.length == 0">
                      <VCard>
                        <VPlaceholderSection title="Not found" subtitle="There is no data that match your query."
                          class="my-6">
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                              alt="" />
                          </template>
                        </VPlaceholderSection>
                      </VCard>
                    </div>
                    <div class="grid-item mb-4" v-for="(data, i) in dataPiutang" :key="i" v-else>
                      <div class="top-section">
                        <div class="head">
                          <div class="title-wrap">
                            <div class="columns">
                              <div class="column is-3">
                                <VAvatar size="small" :color="listColor[0]" :initials="'PT'" />
                              </div>
                              <div class="column is-12 mr-3">
                                <h3>{{ data.namarekanan }}</h3>
                                <p>{{ data.idrekanan }}</p>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="body">
                          <div class="columns">
                            <div class="column is-3">
                              <h4 class="heading">Tanggal</h4>
                              <p class="fs-075">{{ data.tglCollect }}</p>
                            </div>
                            <div class="column is-3">
                              <h4 class="heading">Keterangan</h4>
                              <p class="fs-075">{{ data.keterangan }}</p>
                            </div>
                            <div class="column is-3">
                              <h4 class="heading">Penjamin</h4>
                              <p class="fs-075">{{ data.namarekanan }}</p>
                            </div>
                            <div class="column is-3">
                              <h4 class="heading">No Reg</h4>
                              <p class="fs-075">{{ data.noCollect }}</p>
                            </div>
                          </div>
                          <div class="columns mt-5-min">
                            <div class="column is-3">
                              <h4 class="heading">Bayar </h4>
                              <p class="fs-075 font-bold">{{ H.formatRp(data.bayar, 'Rp. ') }}</p>
                            </div>
                            <div class="column is-3">
                              <h4 class="heading">Piutang </h4>
                              <p class="fs-075 font-bold">{{ H.formatRp(data.piutang, 'Rp. ') }}</p>
                            </div>
                            <div class="column is-3">
                              <h4 class="heading">Admin </h4>
                              <p class="fs-075 font-bold">{{ H.formatRp(data.adm, 'Rp. ') }}</p>
                            </div>
                            <div class="column is-3">
                              <h4 class="heading">Saldo </h4>
                              <p class="fs-075 font-bold">{{ H.formatRp(data.saldo, 'Rp. ') }}
                              </p>
                            </div>
                          </div>
                        </div>
                        <div class="bottom-section is-custom">
                          <div class="column is-12">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </VCard>
              </div>
              <div class="column is-4">
                <VCard>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <input class="input is-rounded" v-model="item.terbilang" disabled />
                    </div>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <VButton @click="Back()" class="is-fullwidth" icon="lnir lnir-arrow-left rem-100" light
                        dark-outlined> Kembali
                      </VButton>
                    </div>
                    <div class="column is-6">
                      <VButton @click="Cetak()" type="button" icon="feather:printer" class="is-fullwidth mr-3"
                        color="warning" raised> Cetak
                      </VButton>
                    </div>
                  </div>
                </VCard>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Kartu Piutang Perusahaan- ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let rekananfk = useRoute().query.rekananfk as string
let noposting = useRoute().query.posting as string
let listColor: any = ref(Object.keys(useThemeColors()))
const dataPiutang: any = ref({});
const item: any = reactive([]);
const isLoading: boolean = ref(false);


const fetchData = async () => {
  isLoading.value = true;
  useApi().get(
    `/piutang/daftar-kartu-piutang-perusahaan?idPerusahaan=${rekananfk}&noposting=${noposting}`).then((response: any) => {
      dataPiutang.value = response[0].data
      item.terbilang = response[0].terbilang
      item.rekanan = response[0].data[0].idrekanan
      item.namaperusahaan = response[0].data[0].namarekanan
      let piutang = 0;
      response[0].data.forEach((element: any, index: number) => {
        piutang += parseFloat(element.piutang)
      });
      item.piutang = piutang;
      isLoading.value = false;
    })
}
const Back = () => {
  window.history.back()
}
const Cetak = () => {
  H.printBlade(`report/piutang/cetak-kartu-piutang-perusahaan?idPerusahaan=${rekananfk}&kodePosting=${noposting}`)
}
fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}

.grid-item {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 220px;
  padding: 20px;
  background: var(--white);
  border: 1px solid var(--fade-grey-dark-3);
  border-radius: var(--radius-large);

  .top-section {
    .head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;

      h3 {
        font-size: 1rem;
        font-family: var(--font-alt);
        color: var(--dark-text);
        font-weight: 600;
      }
    }

    .body {
      p {
        font-family: var(--font);
        color: var(--light-text);
      }
    }
  }

  .bottom-section {
    display: flex;

    .foot-block {
      margin-right: 30px;

      .heading {
        font-family: var(--font-alt);
        font-size: 0.75rem;
        color: var(--light-text-dark-22);
      }

      .developers {
        display: flex;

        .v-avatar {
          margin-right: 6px;
        }
      }
    }
  }
}
</style>
