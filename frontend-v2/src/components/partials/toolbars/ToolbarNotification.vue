<script setup lang="ts">
import { ref } from 'vue'
import { useApi } from '/@src/composable/useApi'
import { useDropdown } from '/@src/composable/useDropdown'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import * as H from '/@src/utils/appHelper'
import Badge from 'primevue/badge';
import { state, socket } from "/@src/socket.js";
import { useRoute, useRouter, } from 'vue-router'
const dropdownElement = ref<HTMLElement>()
const dropdown = useDropdown(dropdownElement)
const listNotif: any = ref([])
const router = useRouter()
const soundNotif = "/audio/iphone_text_message.mp3"

// SOCKET DIMATIIN DULU, UPDATE WHEN ITS READY!
// socket.on('set-server-socket', function (e) {
//   let json = JSON.parse(e);

//   if (json.name == "sendNotification") {
//     let objectData = json.body

//     if (objectData.group == 'mapping_login') {
//       if (H.mapLoginUserToRuangan().length > 0) {
//         for (let x = 0; x < H.mapLoginUserToRuangan().length; x++) {
//           const element = H.mapLoginUserToRuangan()[x];
//           if (element.id == objectData.idRuanganTujuan) {
//             if (listNotif.value.length == 0) {
//               listNotif.value.push(objectData)
//             } else {
//               if (!listNotif.value.some(x => x.norec === objectData.norec)) {
//                 listNotif.value.push(objectData);
//               }
//             }
//             sortingNotif()
//             H.alert('warning', objectData.pesanNotifikasi, 'Notif ' + objectData.jenis)

//             let audio = new Audio(soundNotif);
//             audio.play();
//             setTimeout(() => {
//               audio.pause();
//             }, (8000)); //8 detik

//           }
//         }
//       }

//     } else if (objectData.group == 'pegawai') {
//       if (H.pegawaiLogin().id == objectData.idPegawai) {
//         if (listNotif.value.length == 0) {
//           listNotif.value.push(objectData)
//         } else {
//           if (!listNotif.value.some(x => x.norec === objectData.norec)) {
//             listNotif.value.push(objectData);
//           }
//         }
//         sortingNotif()
//         H.alert('warning', objectData.pesanNotifikasi, 'Notif ' + objectData.jenis)

//         let audio = new Audio(soundNotif);
//         audio.play();
//         setTimeout(() => {
//           audio.pause();
//         }, (8000)); //8 detik
//       }


//     } else if (objectData.group == 'kelompokuser') {
//       if (H.kelompokUserId() == objectData.idKelompokUser) {
//         if (listNotif.value.length == 0) {
//           listNotif.value.push(objectData)
//         } else {
//           if (!listNotif.value.some(x => x.norec === objectData.norec)) {
//             listNotif.value.push(objectData);
//           }
//         }
//         sortingNotif()
//         H.alert('warning', objectData.pesanNotifikasi, 'Notif ' + objectData.jenis)

//         let audio = new Audio(soundNotif);
//         audio.play();
//         setTimeout(() => {
//           audio.pause();
//         }, (8000)); //8 detik
//       }
//     }
//   }
// })

const sortingNotif = () => {
  listNotif.value.sort(function (a, b) {
    if (a.tgl < b.tgl) {
      return -1;
    }
    if (a.tgl > b.tgl) {
      return 1;
    }
    return 0;
  })
}
const loadNotif = () => {
  listNotif.value = []

  // useApi().postNoMessage('general/store-notif', { method: 'get' }).then((e: any) => {
  //   if (e.response.data.length > 0) {
  //     let response = group(e.response.data)
  //     for (let x = 0; x < response.length; x++) {
  //       const element = response[x];
  //       if (element.group == 'mapping_login') {
  //         if (H.mapLoginUserToRuangan().length > 0) {
  //           for (let z = 0; z < H.mapLoginUserToRuangan().length; z++) {
  //             const element2 = H.mapLoginUserToRuangan()[z];
  //             if (element2.id == element.ruangantujuanfk) {
  //               listNotif.value.push({
  //                 norec: element.norec,
  //                 judul: element.judul,
  //                 jenis: element.jenis,
  //                 pesanNotifikasi: element.keterangan,
  //                 idRuanganAsal: element.ruanganasalfk,
  //                 idRuanganTujuan: element.ruangantujuanfk,
  //                 ruanganAsal: element.ruanganasal,
  //                 ruanganTujuan: element.ruangantujuan,
  //                 kelompokUser: element.kelompokuser,
  //                 idKelompokUser: element.kelompokuserfk,
  //                 dataArray: element.dataarray,
  //                 urlForm: element.urlform,
  //                 idPegawai: element.pegawaifk,
  //                 namaFungsiFrontEnd: null,
  //                 tgl: element.tgl,
  //                 namapegawai: element.namapegawai,
  //                 tgl_string: element.tgl_string,
  //                 norec_trans: element.norec_trans
  //               })
  //             }
  //           }
  //         }
  //       } else if (element.group == 'pegawai') {
  //         if (H.pegawaiLogin().id == element.pegawaifk) {
  //           listNotif.value.push({
  //             norec: element.norec,
  //             judul: element.judul,
  //             jenis: element.jenis,
  //             pesanNotifikasi: element.keterangan,
  //             idRuanganAsal: element.ruanganasalfk,
  //             idRuanganTujuan: element.ruangantujuanfk,
  //             ruanganAsal: element.ruanganasal,
  //             ruanganTujuan: element.ruangantujuan,
  //             kelompokUser: element.kelompokuser,
  //             idKelompokUser: element.kelompokuserfk,
  //             dataArray: element.dataarray,
  //             urlForm: element.urlform,
  //             idPegawai: element.pegawaifk,
  //             namaFungsiFrontEnd: null,
  //             tgl: element.tgl,
  //             namapegawai: element.namapegawai,
  //             tgl_string: element.tgl_string,
  //             norec_trans: element.norec_trans
  //           })
  //         }
  //       } else if (element.group == 'kelompokuser') {
  //         if (H.kelompokUserId() == element.kelompokuserfk) {
  //           listNotif.value.push({
  //             norec: element.norec,
  //             judul: element.judul,
  //             jenis: element.jenis,
  //             pesanNotifikasi: element.keterangan,
  //             idRuanganAsal: element.ruanganasalfk,
  //             idRuanganTujuan: element.ruangantujuanfk,
  //             ruanganAsal: element.ruanganasal,
  //             ruanganTujuan: element.ruangantujuan,
  //             kelompokUser: element.kelompokuser,
  //             idKelompokUser: element.kelompokuserfk,
  //             dataArray: element.dataarray,
  //             urlForm: element.urlform,
  //             idPegawai: element.pegawaifk,
  //             namaFungsiFrontEnd: null,
  //             tgl: element.tgl,
  //             namapegawai: element.namapegawai,
  //             tgl_string: element.tgl_string,
  //             norec_trans: element.norec_trans
  //           })
  //         }
  //       }
  //     }

  //   }
  //   // console.log(listNotif.value)
  // })
}
const group = (result) => {
  let sama = false
  let arrGroup = [];
  for (let i = 0; i < result.length; i++) {
    sama = false
    for (let x = 0; x < arrGroup.length; x++) {
      if (arrGroup[x].norec_trans == result[i].norec_trans) {
        sama = true;
      }
    }
    if (sama == false) {
      arrGroup.push(result[i])
    }
  }
  return arrGroup;
}
const formatTimeAgo = (timestamp) => {
  timestamp = new Date(timestamp).getTime(); // Replace with your actual timestamp
  const currentDate = new Date();
  const inputDate = new Date(timestamp);

  const timeDifference = currentDate - inputDate;
  const seconds = Math.floor(timeDifference / 1000);
  const minutes = Math.floor(seconds / 60);
  const hours = Math.floor(minutes / 60);

  if (seconds < 60) {
    return 'now';
  } else if (minutes < 60) {
    return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
  } else if (hours < 24) {
    return `${hours} hour${hours > 1 ? 's' : ''} ago`;
  } else {
    // Customize this part based on your specific needs for longer time intervals
    const days = Math.floor(hours / 24);
    return `${days} day${days > 1 ? 's' : ''} ago`;
  }
}
const goto = (e: any) => {
  for (var i = listNotif.value.length - 1; i >= 0; i--) {
    if (listNotif.value[i].norec_trans == e.norec_trans) {
      listNotif.value.splice(i, 1);
    }
  }
  dropdown.toggle()
  useApi().postNoMessage('general/store-notif', { method: 'delete', norec: e.norec_trans })
  router.push({ name: e.urlForm })
}
loadNotif()
</script>

<template>
  <div class="toolbar-notifications is-hidden-mobile">
    <div ref="dropdownElement" class="dropdown is-spaced is-dots is-right dropdown-trigger">
      <div tabindex="0" class="is-trigger" aria-haspopup="true" @click="dropdown.toggle"
        @keydown.space.prevent="dropdown.toggle">
        <i aria-hidden="true" class="iconify" data-icon="feather:bell"></i>
        <span class="new-indicator pulsate"></span>
      </div>
      <div class="dropdown-menu" role="menu">
        <div class="dropdown-content">
          <div class="heading">
            <div class="heading-left">
              <h6 class="heading-title">
                <Badge :value="listNotif.length > 50 ? '50+' : listNotif.length" v-if="listNotif.length" severity="danger"
                  class="mr-2" /> Notifikasi
              </h6>
            </div>.
            <div class="heading-right">
              <!-- <RouterLink class="notification-link" :to="{ name: 'navbar-notification-page' }">
                Lihat Semua
              </RouterLink> -->
            </div>
          </div>
          <ul class="notification-list">
            <li v-if="listNotif.length == 0">
              Belum Ada Notifikasi
            </li>
            <li v-else>
              <a class="notification-item" v-for="items in listNotif" @click="goto(items)">
                <div class="img-left">
                  <VAvatar class="user-photo" size="small" color="primary" :initials="H.INITIALS(items.judul)" />

                </div>
                <div class="user-content" v-tooltip-prime.top="items.pesanNotifikasi">
                  <p class="user-info">
                    <span class="name">{{ items.judul }}.</span>
                    <!-- {{ items.pesanNotifikasi }}. -->
                  </p>
                  <p class="time">{{ formatTimeAgo(items.tgl) }}</p>
                </div>
              </a>

            </li>

          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="scss">
.p-badge.p-badge-danger {
  background-color: #D32F2F;
  color: #ffffff;
}
</style>
