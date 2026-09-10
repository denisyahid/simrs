
<template>
  <div class="profile-wrapper">
    <div class="profile-header has-text-centered">
      <VAvatar
        size="xl"
        picture="/images/avatars/svg/vuero-1.svg"
        badge="/images/icons/flags/united-states-of-america.svg"
      />

      <h3 class="title is-4 is-narrow is-thin">{{ item.namalengkap }}</h3>
      <p class="light-text">
      </p>
      <div class="profile-stats">
        <div class="profile-stat">
          <i aria-hidden="true" class="lnil lnil-users-alt"></i>
          <span> {{ item.objectjeniskelaminfk }} </span>
        </div>
        <div class="separator"></div>
        <div class="profile-stat">
          <i aria-hidden="true" class="lnil lnil-checkmark-circle"></i>
          <span>{{ item.objectjenispegawaifk }}</span>
        </div>
        <div class="separator"></div>
        <div class="profile-stat">
          <i aria-hidden="true" class="lnil lnil-home"></i>
          <span>{{ item.objectunitkerjapegawaifk }}</span>
        </div>
        
      </div>
    </div>

    <div class="profile-body">
      <div class="columns">
        <div class="column is-8">
          <div class="profile-card">
 
            <div class="profile-card-section">
              <div class="section-title">
                <h4>Tentang Saya</h4>
              <!-- <RouterLink :to="{ name: 'sidebar-layouts-profile-edit' }">
                  <i aria-hidden="true" class="lnil lnil-pencil"></i>
                </RouterLink>--> 
              </div>
              <div class="section-content">
                <div class="experience-wrapper">
                  <div class="experience-item">
                    <img
                    src="/images/pegawai/cal.svg"
                      alt=""
                      @error.once="(event) => onceImageErrored(event, '150x150')"
                    />
                    <div class="meta">
                      <span class="dark-inverted">Tempat Tanggal Lahir</span>
                      <span>
                        <span> {{ item.tempatlahir }}</span>
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span>{{ item.tgllahir }}</span>
                      </span>
                    </div>
                  </div>
                  <div class="experience-item">
                    <img
                    src="/images/pegawai/last.svg"
                      alt=""
                      @error.once="(event) => onceImageErrored(event, '150x150')"
                    />
                    <div class="meta">
                      <span class="dark-inverted">Pendidikan Terakhir</span>
                      <span>
                        <span> {{ item.objectpendidikanterakhirfk }}</span>
          
                      </span>
                    </div>
                  </div>
                  <div class="experience-item">
                    <img
                      src="/images/pegawai/agama.svg"
                      alt=""
                      @error.once="(event) => onceImageErrored(event, '150x150')"
                    />
                    <div class="meta">
                      <span class="dark-inverted">Agama</span>
                      <span>
                        <span> {{ item.objectagamafk }} </span>
                      </span>
                    </div>
                  </div>
                  <div class="experience-item">
                    <img
                      src="/images/pegawai/alamat.svg"
                      alt=""
                      @error.once="(event) => onceImageErrored(event, '150x150')"
                    />
                    <div class="meta">
                      <span class="dark-inverted">Alamat</span>
                      <span>
                        <span> {{ item.alamat }}</span>
                     
                      </span>
                    </div>
                  </div>
                  <div class="experience-item">
                    <img
                      src="/images/avatars/svg/statusmn.svg"
                      alt=""
                      @error.once="(event) => onceImageErrored(event, '150x150')"
                    />
                    <div class="meta">
                      <span class="dark-inverted">Status Pernikahan</span>
                      <span>
                        <span> {{ item.statusperkawinan }}</span>
                      </span>
                    </div>
                  </div>
                  <div class="experience-item">
                    <img
                      src="/images/pegawai/telpon.svg"
                      alt=""
                      @error.once="(event) => onceImageErrored(event, '150x150')"
                    />
                    <div class="meta">
                      <span class="dark-inverted">No. Telepon, Email</span>
                      <span>
                        <span> {{ item.nohandphone }}</span>
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span> {{ item.email }}</span>
                      </span>
                    </div>
                  </div>
                </div>
              
              </div>
            </div>
            <div class="profile-card-section">
              <div class="section-title">
                <h4>Detail Identitas</h4>
                <RouterLink :to="{ name: 'sidebar-layouts-profile-edit-skills' }">
                  <i aria-hidden="true" class="lnil lnil-pencil"></i>
                </RouterLink>
              </div>
              <div class="section-content">
                <div class="languages-wrapper">
                  <div class="languages-item">
                    <VIconWrap picture="/images/pegawai/card.svg">
                      <template #after>
                        <VPeity
                          type="donut"
                          :values="[100, 100]"
                          :fill="['var(--primary)', 'transparent']"
                          :height="50"
                          :inner-radius="22"
                          :radius="8"
                          :width="50"
                        />
                      </template>
                    </VIconWrap>

                    <div class="meta">
                      <span class="dark-inverted">Nomor Identitas</span>
                      <span> {{ item.noidentitas }}</span>
                    </div>
                  </div>
                  <div class="languages-item">
                    <VIconWrap picture="/images/pegawai/ktp.svg">
                      <template #after>
                        <VPeity
                          type="donut"
                          :values="[100, 100]"
                          :fill="['var(--primary)', 'transparent']"
                          :height="50"
                          :inner-radius="22"
                          :width="50"
                        />
                      </template>
                    </VIconWrap>

                    <div class="meta">
                      <span class="dark-inverted">NPWP</span>
                      <span> {{ item.npwp }}</span>
                    </div>
                  </div>
                  <div class="languages-item">
                    <VIconWrap picture="/images/pegawai/str.svg">
                      <template #after>
                        <VPeity
                          type="donut"
                          :values="[100, 100]"
                          :fill="['var(--primary)', 'transparent']"
                          :height="50"
                          :inner-radius="22"
                          :width="50"
                        />
                      </template>
                    </VIconWrap>

                    <div class="meta">
                      <span class="dark-inverted">Nomor BPJS</span>
                      <span> {{ item.nobpjs }}</span>
                    </div>
                  </div>
                  <div class="languages-item">
                    <VIconWrap picture="/images/pegawai/str.svg">
                      <template #after>
                        <VPeity
                          type="donut"
                          :values="[100, 100]"
                          :fill="['var(--primary)', 'transparent']"
                          :height="50"
                          :inner-radius="22"
                          :width="50"
                        />
                      </template>
                    </VIconWrap>

                    <div class="meta">
                      <span class="dark-inverted">Nomor STR</span>
                      <span> {{ item.nostr }}</span>
                    </div>
                  </div>
                  <div class="languages-item">
                    <VIconWrap picture="/images/pegawai/card.svg">
                      <template #after>
                        <VPeity
                          type="donut"
                          :values="[100, 100]"
                          :fill="['var(--primary)', 'transparent']"
                          :height="50"
                          :inner-radius="22"
                          :width="50"
                        />
                      </template>
                    </VIconWrap>

                    <div class="meta">
                      <span class="dark-inverted">NIK Intern / NIP PNS</span>
                      <span> {{ item.nik_intern }} .</span>
                     <span> {{ item.nip_pns }}</span>
                    </div>
                  </div>
                  <div class="languages-item">
                    <VIconWrap picture="/images/pegawai/ktp.svg">
                      <template #after>
                        <VPeity
                          type="donut"
                          :values="[100, 100]"
                          :fill="['var(--primary)', 'transparent']"
                          :height="50"
                          :inner-radius="22"
                          :width="50"
                        />
                      </template>
                    </VIconWrap>

                    <div class="meta">
                      <span class="dark-inverted">Nomor SIP</span>
                      <span> {{ item.nostr }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!--Skills-->
            <div class="profile-card-section">
              <div class="section-title">
                <h4>Data Kepegawaian</h4>
                <RouterLink :to="{ name: 'sidebar-layouts-profile-edit-skills' }">
                  <i aria-hidden="true" class="lnil lnil-pencil"></i>
                </RouterLink>
              </div>
              
              <div class="section-content">
                <div class="experience-wrapper">
                  <div class="experience-item">
                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                    <div class="meta">
                      <span class="dark-inverted">Tanggal Masuk</span>
                        <span> {{ item.tglmasuk }}</span>
                    </div>
                  </div>
                  <div class="experience-item">
                    <i class="fas fa-calendar-times" aria-hidden="true"></i>
                    <div class="meta">
                      <span class="dark-inverted">Tanggal Keluar</span>
                      <span>
                        <span> {{ item.tglkeluar }}</span>
                      </span>
                    </div>
                  </div>
                  <div class="experience-item">
                    <i class="fas fa-user-nurse" aria-hidden="true"></i>
                    <div class="meta">
                      <span class="dark-inverted">Status Pegawai</span>
                      <span>
                        <span> {{ item.objectstatuspegawaifk }}</span>
                      </span>
                    </div>
                  </div>
                  <div class="experience-item">
                    <i class="fas fa-user-nurse" aria-hidden="true"></i>
                    <div class="meta">
                      <span class="dark-inverted">Jabatan Fungsional / Struktural</span>
                      <span>
                        <span> {{ item.objectjabatanfungsionalfk }}</span>
    
                      </span>
                    </div>
                  </div>
                  <div class="experience-item">
                    <i class="fas fa-user-nurse" aria-hidden="true"></i>
                    <div class="meta">
                      <span class="dark-inverted">Kelompok Jabatan</span>
                      <span>
                        <span> {{ item.objectkelompokjabatanfk }}</span>
    
                      </span>
                    </div>
                  </div>
                  <div class="experience-item">
                    <i class="fas fa-user-nurse" aria-hidden="true"></i>
                    <div class="meta">
                      <span class="dark-inverted">Detail Kategory Pegawai</span>
                      <span>
                        <span> {{ item.objectdetailkategorypegawaifk }}</span>
    
                      </span>
                    </div>
                  </div>
                  <div class="experience-item">
                    <i class="fas fa-address-book" aria-hidden="true"></i>
                    <div class="meta">
                      <span class="dark-inverted">Rekening Bank</span>
                      <span>
                        <span> {{ item.bankrekeningnama }}</span>
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span> {{ item.bankrekeningatasnama }}</span>
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span> {{ item.bankrekeningnomor }}</span>
                      </span>
                    </div>
                  </div>
                  <div class="experience-item">
                    <i class="fas fa-id-card" aria-hidden="true"></i>
                    <div class="meta">
                      <span class="dark-inverted">ID Fingerprint</span>
                      <span>
                        <span> {{ item.fingerprintid }} </span>
                      </span>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!--Recommendations-->
        
        </div>
        <div class="column">
      
      
       

          <!--Tools-->
          <div class="profile-card">
            <div class="profile-card-section no-padding">
              <div class="section-title">
                <h4 style="margin-bottom: 1rem;"> Setting Login User </h4>
              </div>
              <div class="tile-grid tile-grid-v2">
          <VPlaceholderPage :class="[dataLogin.length !== 0 && 'is-hidden']" title="Tidak Ada Data."
            subtitle=" Silakan Hubungi Admin untuk Info Lebih Lanjut" larger>
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>

          <!--Tile Grid v1-->

          <div name="list" tag="div" class="columns is-multiline">
            <!--Grid item-->
            <div class="columns is-multiline p-2" style="max-height:200px;overflow: auto;">
              <div v-for="item in dataLogin" :key="item.id" class="column is-12 p-0 pb-2 pl-2 pr-2 ">
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner" @click="edit(item)">
                    <VAvatar size="small" picture="/images/avatars/svg/userlo.png" color="primary" bordered />
                    <div class="meta">
                      <span class="dark-inverted text-elipsis-wrap" style="width:200px !important">{{ item.namauser }}  <i aria-hidden="true" class="lnil lnil-pencil" style="margin-left: 20px;"></i></span>
                     
                    </div>
                   
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
            </div>
          </div>

          <div class="profile-card">
            <div class="profile-card-section no-padding">
              <div class="section-title">
                <h4  style="margin-bottom: 1rem;">Jadwal Kerja </h4>
              </div>
      
        <div class="tile-grid tile-grid-v2">
          <VPlaceholderPage :class="[dataJadwal.length !== 0 && 'is-hidden']" title="Tidak Ada Dokter Praktek Hari Ini."
            subtitle=" Silakan Pilih Ruangan untuk Melihat Jadwal Praktek Dokter" larger>
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>

          <!--Tile Grid v1-->

          <div name="list" tag="div" class="columns is-multiline">
            <!--Grid item-->
            <div class="columns is-multiline p-2" style="max-height:500px;overflow: auto;">
              <div v-for="item in dataJadwal" :key="item.id" class="column is-12 p-0 pb-2 pl-2 pr-2 ">
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner">
                    <VAvatar size="small" picture="/images/avatars/svg/dokter.svg" color="primary" bordered />
                    <div class="meta">
                      <span class="dark-inverted text-elipsis-wrap" style="width:200px !important">{{ item.hari }}</span>
                      <span>
                        <i aria-hidden="true" class="iconify" data-icon="feather:clock" style="padding-right: 3px;"></i>
                        {{ item.jammulai }} s.d {{ item.jamakhir }}</span>
                  

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
    </div>
  </div>
  <Dialog v-model:visible="modalInput" modal :header="(item.id != undefined ? 'Ubah' : 'Simpan') + ' Login User'"
            :style="{ width: '30vw' }">
            <!-- <VModal :open="modalInput" title="Tambah Login User" actions="right" @close="modalInput = false"> -->
            <!-- <template #content>
              <form class="modal-form"> -->
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField label="Nama User">
                  <VControl icon="fas fa-user">
                    <VInput type="text" v-model="item.namauser" placeholder="Nama Login User" class="is-rounded" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <VField label="Kata Sandi">
                  <VControl icon="fas fa-address-card">
                    <VInput type="password" v-model="item.katasandi" placeholder="Kata Sandi" class="is-rounded" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <VField label="Ulangi Kata Sandi">
                  <VControl icon="fas fa-address-card">
                    <VInput type="password" v-model="item.katasandi2" placeholder="Ulangi Kata Sandi" class="is-rounded" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField label="Nama Pegawai">
                  <VControl icon="fas fa-user">
                    <VInput type="text" v-model="item.namalengkap" placeholder="Nama Login User" class="is-rounded" disabled/>
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField label="Nama User">
                  <VControl icon="fas fa-user">
                    <VInput type="text" v-model="item.kelompokuser" placeholder="Nama Login User" class="is-rounded" disabled/>
                  </VControl>
                </VField>
              </div>
            </div>
            <template #footer>
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalInput = false">
                Tutup
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" @click="save()"
                :loading="isLoadingTT"> {{ item.id != undefined ? 'Ubah' : 'Simpan' }}
              </VButton>
            </template>
          </Dialog>
</template>

<script setup lang="ts">
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog';
import { useUserSession } from '/@src/stores/userSession'

useHead({
    title: 'Detail Pegawai - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PEGAWAI = useRoute().query.id as string
let ID_PEGAWAI_SET = ref()
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)

const modalInput = ref(false)
const date = ref(new Date())
const dataJadwal = ref([])
const dataLogin = ref([])
const d_pegawai = ref([])
const d_kelompok = ref([])
const item: any = ref({
    aktif: true
  }
)
const dataSource: any = ref([])

const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})

async function pegawaiByID(id: any) {
    const { data: pegawai } = await useApi().get(`/sysadmin/master-pegawai?id=${id}`)
    item.value.namalengkap = pegawai[0].namalengkap
    item.value.objectjenispegawaifk = pegawai[0].jenispegawai
    item.value.objectjeniskelaminfk = pegawai[0].jeniskelamin
    item.value.objectagamafk = pegawai[0].agama
    item.value.alamat = pegawai[0].alamat
    item.value.noidentitas = pegawai[0].noidentitas
    item.value.npwp = pegawai[0].npwp
    item.value.nosip = pegawai[0].nosip
    item.value.nostr = pegawai[0].nostr
    item.value.nobpjs = pegawai[0].nobpjs
    item.value.tempatlahir = pegawai[0].tempatlahir
    item.value.tgllahir = pegawai[0].tgllahir
    item.value.tglmasuk = pegawai[0].tglmasuk
    item.value.tglkeluar = pegawai[0].tglkeluar
    item.value.bankrekeningnomor = pegawai[0].bankrekeningnomor
    item.value.bankrekeningatasnama = pegawai[0].bankrekeningatasnama
    item.value.bankrekeningnama = pegawai[0].bankrekeningnama
    item.value.objectjabatanfungsionalfk = pegawai[0].namajabatan
    item.value.objectstatuspegawaifk = pegawai[0].statuspegawai
    item.value.objectpendidikanterakhirfk = pegawai[0].pendidikan
    item.value.objectstatusperkawinanfk = pegawai[0].statusperkawinan
    item.value.objectunitkerjapegawaifk = pegawai[0].namaruangan
    item.value.objectkelompokjabatanfk = pegawai[0].namakelompokjabatan
    item.value.objectdetailkategorypegawaifk = pegawai[0].detailkategorypegawai

  }

  const fetchPegawai = async (id: any) => {
  isLoading.value = true
  dataLogin.value = []
  dataJadwal.value = []
  await useApi().get(`/sysadmin/jadwal-kerja?idpegawai=${id}`).then((response) => {
    isLoading.value = false
    dataJadwal.value = response.data
    dataLogin.value = response.login
  })
}
function loadDropdown() {
  d_pegawai.value = []
  useApi().get(
    `/sysadmin/master-tambah-login-user-dropdown`).then((response: any) => {
      d_pegawai.value = response.namalengkap;//.map((e: any) => { return { label: e.namalengkap, value: e.id } })
      d_kelompok.value = response.kelompokuser;//.map((e: any) => { return { label: e.kelompokuser, value: e.id } })
    })
}

function add() {
  modalInput.value = true
}

function edit(e: any) {
  console.log(e)
  item.value.id = e.id
  item.value.statusenabled = e.statusenabled
  item.value.namauser = e.namauser
  item.value.kelompokuser = e.kelompokuser
  modalInput.value = true
}

async function save() {

if (!item.value.namauser) {
  useToaster().error('Nama User harus di isi')
  return
}
// if (!item.value.objectpegawaifk) {
//   useToaster().error('Nama Pegawai harus di isi')
//   return
// }
if (!item.value.katasandi) {
  useToaster().error('Kata Sandi harus di isi')
  return
}
if (item.value.katasandi != item.value.katasandi2) {
  useToaster().error('Kata Sandi tidak sama')
  return
}
var objSave =
{
  'loginuser': {
    'id': item.value.id ? item.value.id : '',
    'namauser': item.value.namauser,
    'objectpegawaifk':useUserSession().getUser().pegawai.id,
    'objectkelompokuserfk': useUserSession().getUser().kelompokUser.id,
    'katasandi': item.value.katasandi,
  }
}
isLoadingTT.value = true
await useApi().post(
  `/sysadmin/save-login-user`, objSave).then((response: any) => {
    isLoadingTT.value = false
    clear()
  
  }, (error) => {
    isLoadingTT.value = false
    // console.log(error)
  })
}

const clear = () =>
{
  item.value = {}
  modalInput.value = false
}

loadDropdown()
fetchPegawai(ID_PEGAWAI)
pegawaiByID(ID_PEGAWAI)


</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/profile-stats';

.is-navbar {
  .profile-wrapper {
    margin-top: 30px;
  }
}

.profile-wrapper {
  max-width: 1040px;
  margin: 0 auto;

  .profile-header {
    text-align: center;

    > img {
      display: block;
      margin: 0 auto;
      max-width: 300px;
    }

    .v-avatar {
      margin: 0 auto 12px;
    }

    .anim-icon {
      margin-bottom: 12px;
    }

    .title {
      margin-bottom: 6px;
    }

    p {
      font-size: 1rem;
      max-width: 540px;
      margin: 0 auto;
      line-height: 1.3;
    }
  }

  .profile-body {
    padding: 10px 0 20px;

    .profile-card {
      @include vuero-s-card;

      padding: 30px;

      &:not(:last-child) {
        margin-bottom: 20px;
      }

      .profile-card-section {
        padding-bottom: 20px;

        &:not(:last-child) {
          margin-bottom: 20px;
          border-bottom: 1px solid var(--fade-grey-dark-4);
        }

        &.no-padding {
          padding-bottom: 0;
        }

        .section-title {
          display: flex;
          align-items: center;
          margin-bottom: 12px;

          h4 {
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            color: var(--dark-text);
            margin-right: 6px;
          }

          i {
            color: var(--primary);
          }

          .action-link {
            position: relative;
            top: -2px;
            margin-left: auto;
            text-transform: uppercase;
            font-size: 0.8rem;
          }

          .control {
            margin-left: auto;

            .form-switch {
              transform: scale(0.8);
            }
          }
        }

        .section-content {
          .description {
            font-size: 0.95rem;
          }

          .experience-wrapper {
            display: flex;
            flex-wrap: wrap;
            margin-left: -8px;
            margin-right: -8px;

            .experience-item {
              display: flex;
              align-items: center;
              width: calc(50% - 16px);
              margin: 8px;

              img {
                display: block;
                width: 50px;
                min-width: 50px;
                height: 50px;
                border-radius: var(--square-rounded);
                border: 1px solid var(--fade-grey-dark-4);
              }

              .meta {
                margin-left: 10px;

                > span {
                  font-family: var(--font);
                  display: block;

                  &:first-child {
                    font-family: var(--font-alt);
                    font-weight: 600;
                    color: var(--dark-text);
                    font-size: 0.85rem;
                  }

                  &:nth-child(2),
                  &:nth-child(3) {
                    font-size: 0.85rem;
                    color: var(--light-text);

                    i {
                      position: relative;
                      top: -2px;
                      font-size: 4px;
                      margin: 0 6px;
                    }
                  }

                  &:nth-child(3) {
                    color: var(--primary);
                  }

                  span {
                    display: inline-block;
                  }
                }
              }
            }
          }

          .languages-wrapper {
            display: flex;
            flex-wrap: wrap;
            margin-left: -8px;
            margin-right: -8px;

            .languages-item {
              display: flex;
              align-items: center;
              width: calc(50% - 16px);
              margin: 8px;

              .icon-wrap {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 50px;
                min-width: 50px;
                height: 50px;

                img {
                  position: absolute;
                  top: 50%;
                  left: 50%;
                  transform: translate(-50%, -50%);
                  display: block;
                  width: 32px;
                  min-width: 32px;
                  height: 32px;
                  border-radius: var(--radius-rounded);
                  border: 1px solid var(--fade-grey-dark-4);
                }
              }

              .meta {
                margin-left: 10px;

                > span {
                  display: block;
                  font-family: var(--font);

                  &:first-child {
                    font-family: var(--font-alt);
                    font-weight: 600;
                    color: var(--dark-text);
                    font-size: 0.9rem;
                  }

                  &:nth-child(2) {
                    font-size: 0.85rem;
                    color: var(--light-text);
                  }

                  span {
                    display: inline-block;
                  }
                }
              }
            }
          }

          .skills-wrapper {
            .skills-item {
              display: flex;
              align-items: center;

              &:not(:last-child) {
                margin-bottom: 16px;
              }

              .icon-wrap {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                min-width: 42px;
                height: 42px;
                border-radius: var(--radius-rounded);
                border: 1px solid var(--primary);

                &.has-icon {
                  background: var(--fade-grey-light-2);
                  border: 1px solid var(--fade-grey-dark-3);
                  color: var(--light-text);

                  i {
                    font-size: 1.4rem;
                  }
                }

                img {
                  display: block;
                  width: 34px;
                  min-width: 34px;
                  height: 34px;
                  border-radius: var(--radius-rounded);
                  border: 1px solid var(--fade-grey-dark-4);
                }

                .count {
                  position: absolute;
                  bottom: 0;
                  right: -4px;
                  display: flex;
                  justify-content: center;
                  align-items: center;
                  width: 22px;
                  height: 22px;
                  border-radius: var(--radius-rounded);
                  background: var(--white);
                  border: 1px solid var(--primary);

                  span {
                    font-family: var(--font);
                    font-weight: 500;
                    font-size: 0.75rem;
                  }
                }
              }

              .skill-info {
                font-family: var(--font);
                margin-left: 12px;
                line-height: 1.3;

                span {
                  display: block;

                  &:first-child {
                    font-family: var(--font-alt);
                    font-weight: 600;
                    color: var(--dark-text);
                    font-size: 0.9rem;
                  }

                  &:nth-child(2) {
                    font-size: 0.9rem;
                    color: var(--light-text);
                  }
                }
              }

              .people {
                margin-left: auto;
                display: flex;
                justify-content: flex-end;

                .v-avatar {
                  margin: 0 4px;
                }
              }
            }
          }

          .recommendations-wrapper {
            display: flex;
            flex-wrap: wrap;
            margin-left: -8px;
            margin-right: -8px;

            .recommendations-item {
              width: calc(50% - 16px);
              margin: 8px;
              background: var(--fade-grey-light-3);
              text-align: center;
              padding: 30px 20px;
              border-radius: var(--radius);

              > .v-avatar {
                display: block;
                margin: 0 auto 8px;
              }

              h3 {
                font-size: 1rem;
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
                margin-bottom: 8px;
              }

              p {
                font-size: 0.85rem;
                margin-bottom: 16px;
              }

              .meta {
                span {
                  display: block;

                  &:first-child {
                    font-weight: 600;
                    font-family: var(--font-alt);
                    font-size: 0.95rem;
                    color: var(--primary);
                  }

                  &:nth-child(2) {
                    font-size: 0.9rem;
                    color: var(--light-text);
                  }
                }
              }
            }
          }

          .network-notifications {
            h3 {
              font-family: var(--font-alt);
              font-size: 0.9rem;
              margin-bottom: 6px;
            }

            p {
              font-size: 0.9rem;
              max-width: 480px;
            }
          }

          .tools-wrapper {
            padding-top: 12px;

            .tools-item {
              display: flex;
              align-items: center;
              margin-bottom: 16px;

              &:last-child {
                margin-bottom: 0;
              }

              .icon-wrap {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 50px;
                min-width: 50px;
                height: 50px;

                img {
                  position: absolute;
                  top: 50%;
                  left: 50%;
                  transform: translate(-50%, -50%);
                  display: block;
                  width: 32px;
                  min-width: 32px;
                  height: 32px;
                  border-radius: var(--radius-rounded);
                  border: 1px solid transparent;
                }
              }

              .meta {
                margin-left: 10px;

                > span {
                  display: block;
                  font-family: var(--font);

                  &:first-child {
                    font-family: var(--font-alt);
                    font-weight: 600;
                    color: var(--dark-text);
                    font-size: 0.9rem;
                  }

                  &:nth-child(2) {
                    font-size: 0.85rem;
                    color: var(--light-text);
                  }

                  span {
                    display: inline-block;
                  }
                }
              }
            }
          }

          .people-wrapper {
            padding-top: 12px;

            .people-item {
              display: flex;
              align-items: center;
              margin-bottom: 16px;

              &:last-child {
                margin-bottom: 0;
              }

              .meta {
                margin-left: 10px;

                > span {
                  display: block;
                  font-family: var(--font);

                  &:first-child {
                    font-family: var(--font-alt);
                    font-weight: 600;
                    color: var(--dark-text);
                    font-size: 0.9rem;
                  }

                  &:nth-child(2) {
                    font-size: 0.85rem;
                    color: var(--light-text);
                  }

                  span {
                    display: inline-block;
                  }
                }
              }
            }
          }

          .more-button {
            padding-top: 16px;

            .button {
              min-width: 180px;
              font-family: var(--font);
              text-transform: uppercase;
              font-size: 0.8rem;
              font-weight: 500;
              margin: 0 auto;
              color: var(--light-text);
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .profile-wrapper {
    .profile-header {
      .v-avatar {
        .badge {
          border-color: var(--dark-sidebar-light-6);
        }
      }
    }

    .profile-body {
      .profile-card {
        @include vuero-card--dark;

        .profile-card-section {
          border-color: var(--dark-sidebar-light-12);

          .section-title {
            h4 {
              color: var(--dark-dark-text);
            }

            i {
              color: var(--primary);
            }
          }

          .section-content {
            .icon-wrap {
              > img {
                border-color: var(--dark-sidebar-light-12) !important;
              }
            }

            .experience-wrapper {
              .experience-item {
                > img {
                  border-color: var(--dark-sidebar-light-12);
                }

                .meta {
                  > span {
                    &:nth-child(3) {
                      color: var(--primary);
                    }
                  }
                }
              }
            }

            .skills-wrapper {
              .skills-item {
                .icon-wrap {
                  border-color: var(--primary) !important;

                  &.has-icon,
                  &.has-img {
                    background: var(--dark-sidebar-light-2) !important;
                    border-color: var(--dark-sidebar-light-12) !important;
                  }
                }

                .people {
                  .v-avatar {
                    &:last-child {
                      .is-fake {
                        background: var(--dark-sidebar-light-2);
                        border: 1px solid var(--dark-sidebar-light-12);
                      }
                    }
                  }
                }
              }
            }

            .recommendations-wrapper {
              .recommendations-item {
                background: var(--dark-sidebar-light-2);
                border-color: var(--dark-sidebar-light-12);

                .meta {
                  span {
                    &:first-child {
                      color: var(--primary);
                    }
                  }
                }
              }
            }

            .more-button {
              .button {
                background: var(--dark-sidebar-light-2);
                border-color: var(--dark-sidebar-light-12);
              }
            }
          }
        }
      }
    }
  }

  .icon-wrap,
  .icon-wrap.is-placeholder {
    background: var(--dark-sidebar-light-2) !important;
    border-color: var(--dark-sidebar-light-12) !important;
  }
}

@media only screen and (max-width: 767px) {
  .profile-wrapper {
    .profile-body {
      .profile-card {
        padding: 20px;

        .profile-card-section {
          .section-content {
            .experience-wrapper,
            .languages-wrapper,
            .recommendations-wrapper {
              .experience-item,
              .languages-item,
              .recommendations-item {
                width: calc(100% - 16px);
              }
            }

            .skills-wrapper {
              .skills-item {
                .people {
                  .v-avatar {
                    &:not(:last-child) {
                      display: none !important;
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
</style>
