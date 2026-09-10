import { useNotyf } from '/@src/composable/useNotyf'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import { useUserSession } from '/@src/stores/userSession'
import { useRoute, useRouter } from 'vue-router'
import { useToaster } from '/@src/composable/toaster'
import { state, socket } from "/@src/socket.js";
import { useApi } from '/@src/composable/useApi'
import * as XLSX from "xlsx";
let maps: any = [];

let mapsCache: any = [];
export function formatRp(value: any, currency: any): string {
  if (value == undefined || value == 'null') value = 0

  const formattedValue = parseFloat(value).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");

  // const formattedValue = numericValue.toLocaleString('id-ID', {
  //   minimumFractionDigits: 2,
  // })

  return currency + ' ' + formattedValue
}
type typeNotify =
  | 'success'
  | 'error'
  | 'info'
  | 'purple'
  | 'orange'
  | 'primary'
  | 'blue'
  | 'green'
  | 'warning'

export function alert(type: typeNotify, message: any, title: any = null): any {
  const toast = useToaster()
  title = title ? title : 'Info'
  if (type == 'success') {
    toast.success(message, title)
    // useNotyf().success(message)
  } else if (type == 'error') {
    toast.error(message, title)
    // useNotyf().error(message)
  } else if (type == 'info') {
    toast.info(message, title)
    // useNotyf().info(message)
  } else if (type == 'purple') {
    useNotyf().purple(message)
  } else if (type == 'orange') {
    useNotyf().orange(message)
  } else if (type == 'primary') {
    toast.success(message, title)
    // useNotyf().primary(message)
  } else if (type == 'blue') {
    useNotyf().blue(message)
  } else if (type == 'green') {
    useNotyf().green(message)
  } else if (type == 'warning') {
    toast.warn(message, title)
    // useNotyf().warning(message)
  } else {
    useNotyf().purple(message)
  }
}

export function formatDate(date: any, format: any): any {
  if (!date || !moment(date).isValid()) {
    return '-';
  }
  return moment(new Date(date)).format(format)
}

export function formatDateToLocalString(date: any) {
  return new Date(date).toLocaleDateString('id-ID', { year: "numeric", month: "long", day: "numeric" })
}

export function formatMonthOnly(date: any) {
  return new Date(date).toLocaleDateString('id-ID', { year: "numeric", month: "long" })
}

export function formatDateIndo(tgl: any): any {
  var date: any = new Date(tgl)
  var tahun = date.getFullYear()
  var bulan = date.getMonth()
  var tanggal = date.getDate()
  var hari = date.getDay()
  var jam = date.getHours()
  var menit = date.getMinutes()
  var detik = date.getSeconds()
  switch (hari) {
    case 0:
      hari = 'Minggu'
      break
    case 1:
      hari = 'Senin'
      break
    case 2:
      hari = 'Selasa'
      break
    case 3:
      hari = 'Rabu'
      break
    case 4:
      hari = 'Kamis'
      break
    case 5:
      hari = "Jum'at"
      break
    case 6:
      hari = 'Sabtu'
      break
  }
  switch (bulan) {
    case 0:
      bulan = 'Januari'
      break
    case 1:
      bulan = 'Februari'
      break
    case 2:
      bulan = 'Maret'
      break
    case 3:
      bulan = 'April'
      break
    case 4:
      bulan = 'Mei'
      break
    case 5:
      bulan = 'Juni'
      break
    case 6:
      bulan = 'Juli'
      break
    case 7:
      bulan = 'Agustus'
      break
    case 8:
      bulan = 'September'
      break
    case 9:
      bulan = 'Oktober'
      break
    case 10:
      bulan = 'November'
      break
    case 11:
      bulan = 'Desember'
      break
  }
  return hari + ', ' + tanggal + ' ' + bulan + ' ' + tahun + ' ' + (jam.toString().length == 2 ? jam : "0" + jam) + ':' + (menit.toString().length == 2 ? menit : "0" + menit)
}


export function getTime(jam: any, menit: any): any {

  let hours = new Date(jam).getHours()
  let minutes = new Date(menit).getMinutes()

  return (hours.toString().length == 2 ? hours : "0" + hours) + " : " + (minutes.toString().length == 2 ? minutes : "0" + minutes)
}

export function formatDateNoTime(tgl: any): any {
  var date: any = new Date(tgl)
  var tahun = date.getFullYear()
  var bulan = date.getMonth()
  var tanggal = date.getDate()
  var hari = date.getDay()
  var jam = date.getHours()
  var menit = date.getMinutes()
  var detik = date.getSeconds()
  switch (hari) {
    case 0:
      hari = 'Minggu'
      break
    case 1:
      hari = 'Senin'
      break
    case 2:
      hari = 'Selasa'
      break
    case 3:
      hari = 'Rabu'
      break
    case 4:
      hari = 'Kamis'
      break
    case 5:
      hari = "Jum'at"
      break
    case 6:
      hari = 'Sabtu'
      break
  }
  switch (bulan) {
    case 0:
      bulan = 'Januari'
      break
    case 1:
      bulan = 'Februari'
      break
    case 2:
      bulan = 'Maret'
      break
    case 3:
      bulan = 'April'
      break
    case 4:
      bulan = 'Mei'
      break
    case 5:
      bulan = 'Juni'
      break
    case 6:
      bulan = 'Juli'
      break
    case 7:
      bulan = 'Agustus'
      break
    case 8:
      bulan = 'September'
      break
    case 9:
      bulan = 'Oktober'
      break
    case 10:
      bulan = 'November'
      break
    case 11:
      bulan = 'Desember'
      break
  }
  return tanggal + ' ' + bulan + ' ' + tahun
}
export function formatDateIndoSimple(tgl: any): any {
  var date: any = new Date(tgl)
  var tahun = date.getFullYear().toString().substr(2, 2)
  var bulan = date.getMonth()
  var tanggal = date.getDate()
  var hari = date.getDay()
  var jam = date.getHours()
  var menit = date.getMinutes()
  var detik = date.getSeconds()
  switch (hari) {
    case 0:
      hari = 'Ming'
      break
    case 1:
      hari = 'Sen'
      break
    case 2:
      hari = 'Sel'
      break
    case 3:
      hari = 'Rab'
      break
    case 4:
      hari = 'Kam'
      break
    case 5:
      hari = 'Jum'
      break
    case 6:
      hari = 'Sab'
      break
  }
  switch (bulan) {
    case 0:
      bulan = 'Jan'
      break
    case 1:
      bulan = 'Feb'
      break
    case 2:
      bulan = 'Mar'
      break
    case 3:
      bulan = 'Apr'
      break
    case 4:
      bulan = 'Mei'
      break
    case 5:
      bulan = 'Jun'
      break
    case 6:
      bulan = 'Jul'
      break
    case 7:
      bulan = 'Agu'
      break
    case 8:
      bulan = 'Sep'
      break
    case 9:
      bulan = 'Okt'
      break
    case 10:
      bulan = 'Nov'
      break
    case 11:
      bulan = 'Des'
      break
  }
  return hari + ', ' + tanggal + ' ' + bulan + ' ' + tahun + ' ' + (jam.toString().length == 2 ? jam : "0" + jam) + ':' + (menit.toString().length == 2 ? menit : "0" + menit)
}

export function formatDateOnly(tgl: any): any {
  var date: any = new Date(tgl)
  var tahun = date.getFullYear().toString()
  // var tahun = date.getFullYear().toString().substr(2, 2)
  var bulan = date.getMonth()
  var tanggal = date.getDate()
  var hari = date.getDay()

  switch (bulan) {
    case 0:
      bulan = 'Jan'
      break
    case 1:
      bulan = 'Feb'
      break
    case 2:
      bulan = 'Mar'
      break
    case 3:
      bulan = 'Apr'
      break
    case 4:
      bulan = 'Mei'
      break
    case 5:
      bulan = 'Jun'
      break
    case 6:
      bulan = 'Jul'
      break
    case 7:
      bulan = 'Agu'
      break
    case 8:
      bulan = 'Sep'
      break
    case 9:
      bulan = 'Okt'
      break
    case 10:
      bulan = 'Nov'
      break
    case 11:
      bulan = 'Des'
      break
  }
  return tanggal + ' ' + bulan + ' ' + tahun
}
export function formatDateIndoSimpleNoDay(tgl: any): any {
  var date: any = new Date(tgl)
  var tahun = date.getFullYear().toString()
  var bulan = date.getMonth()
  var tanggal = date.getDate()
  var hari = date.getDay()
  var jam = date.getHours()
  var menit = date.getMinutes()
  var detik = date.getSeconds()

  switch (bulan) {
    case 0:
      bulan = 'Jan'
      break
    case 1:
      bulan = 'Feb'
      break
    case 2:
      bulan = 'Mar'
      break
    case 3:
      bulan = 'Apr'
      break
    case 4:
      bulan = 'Mei'
      break
    case 5:
      bulan = 'Jun'
      break
    case 6:
      bulan = 'Jul'
      break
    case 7:
      bulan = 'Agu'
      break
    case 8:
      bulan = 'Sep'
      break
    case 9:
      bulan = 'Okt'
      break
    case 10:
      bulan = 'Nov'
      break
    case 11:
      bulan = 'Des'
      break
  }
  return tanggal + ' ' + bulan + ' ' + tahun + ' ' + (jam.toString().length == 2 ? jam : "0" + jam) + ':' + (menit.toString().length == 2 ? menit : "0" + menit)
}
export function diff_day(first: any, second: any): any {
  if (first instanceof Date) {
  } else {
    first = new Date(first)
    second = new Date(second)
  }

  let diffTime = Math.abs(second - first);
  let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  diffDays = diffDays + 1
  return diffDays;
}
export function cacheHelper() {
  // var maps: any = [];
  return {
    get(name: any) {
      var temp = window.localStorage.getItem('cacheHelper_');
      if (temp !== undefined && temp !== null && maps.length === 0)
        maps = JSON.parse(temp);

      for (var key in maps) {
        if (maps.hasOwnProperty(key)) {
          var element = maps[key];
          if (element.name === name)
            return element.value;
        }
      }
      return undefined;
    },
    set(name: any, value: any) {
      var item = undefined;
      for (var key in maps) {
        if (maps.hasOwnProperty(key)) {
          var element = maps[key];
          if (element.name === name) {
            item = element;
            maps[key] = {
              name: name,
              value: value
            };
            break;
          }
        }
      }
      if (item === undefined)
        maps.push({
          name: name,
          value: value
        });
      else item = {
        name: name,
        value: value
      };
      window.localStorage.setItem('cacheHelper_', JSON.stringify(maps));
    },
    delete(name: any) {
      var temp = window.localStorage.getItem('cacheHelper_');
      if (temp !== undefined && temp !== null && maps.length === 0)
      maps = JSON.parse(temp);
      for (let i = 0; i < maps.length; i++) {
        if (maps[i].name == name) {
          maps.splice(i, 1);
          window.localStorage.setItem('cacheHelper_', JSON.stringify(maps));
          return;
        }
      }
    }
  }
}

export function cachePeriode() {
  var maps: any = [];
  return {
    get(name: any) {
      var temp = window.sessionStorage.getItem('periode');
      if (temp !== undefined && temp !== null && maps.length === 0)
        maps = JSON.parse(temp);

      for (var key in maps) {
        if (maps.hasOwnProperty(key)) {
          var element = maps[key];
          if (element.name === name)
            return element.value;
        }
      }
      return undefined;
    },
    set(name: any, value: any) {
      var item = undefined;
      for (var key in maps) {
        if (maps.hasOwnProperty(key)) {
          var element = maps[key];
          if (element.name === name) {
            item = element;
            maps[key] = {
              name: name,
              value: value
            };
            break;
          }
        }
      }
      if (item === undefined)
        maps.push({
          name: name,
          value: value
        });
      else item = {
        name: name,
        value: value
      };
      window.sessionStorage.setItem('periode', JSON.stringify(maps));
    }
  }
}

export function iconPoli(): any {
  return [
    {
      'name': 'anak',
      'value': '/images/ruang/anak.png',
      'icon': '/images/ruang/anak.png'
    },
    {
      'name': 'jantung',
      'value': '/images/ruang/jantung.png',
      'icon': '/images/ruang/jantung.png',

    },
    {
      'name': 'tht',
      'value': '/images/ruang/tht.png',
      'icon': '/images/ruang/tht.png'
    },
    {
      'name': 'obgyn',
      'value': '/images/ruang/obgyn.png',
      'icon': '/images/ruang/obgyn.png'
    },
    {
      'name': 'gigi',
      'value': '/images/ruang/gigi.png',
      'icon': '/images/ruang/gigi.png'
    },
    {
      'name': 'umum',
      'value': '/images/ruang/umum.png',
      'icon': '/images/ruang/umum.png'
    }
  ]
}
export function assets(): any {
  return {
    notFound: 'Data belum ada.',
    notFoundSubtitle: 'Sepertinya data ini belum di inputkan, silahkan melakukan penginputan terlebih dahulu.',
    iconNotFound: '/@src/assets/illustrations/placeholders/search-4.svg',
    iconNotFound_rev: '/images/simrs/not-found-emr.png',
    iconNotFoundDark: '/@src/assets/illustrations/placeholders/search-4-dark.svg',
    iconNotFoundCalendar: '/images/simrs/not-found-calendar.png',
    iconNotFoundList: '/images/simrs/not-found-list.png',

  }
}
export function formatDateOnlyLong(tgl: any): any {
  var date: any = new Date(tgl)
  var tahun = date.getFullYear()
  var bulan = date.getMonth()
  var tanggal = date.getDate()
  var hari = date.getDay()

  switch (bulan) {
    case 0:
      bulan = 'Jan'
      break
    case 1:
      bulan = 'Feb'
      break
    case 2:
      bulan = 'Mar'
      break
    case 3:
      bulan = 'Apr'
      break
    case 4:
      bulan = 'Mei'
      break
    case 5:
      bulan = 'Jun'
      break
    case 6:
      bulan = 'Jul'
      break
    case 7:
      bulan = 'Agu'
      break
    case 8:
      bulan = 'Sep'
      break
    case 9:
      bulan = 'Okt'
      break
    case 10:
      bulan = 'Nov'
      break
    case 11:
      bulan = 'Des'
      break
  }
  return tanggal + ' ' + bulan + ' ' + tahun
}
export function apiBackend(): any {
  return import.meta.env.VITE_API_BASE_URL
}
export function printBlade(url: any): any {
  // fetch(import.meta.env.VITE_API_BASE_URL + url, { /*method: 'POST'*/method: 'GET', headers: { 'token': useUserSession().token } })
  //   .then((response) => response.blob())
  //   .then((blob) => {
  //     const _url = window.URL.createObjectURL(blob);
  //     window.open(_url, '_blank').focus();
  //   }).catch((err) => {
  //     console.log(err);
  //   });
  window.open(import.meta.env.VITE_API_BASE_URL + url
    + "&user=" + useUserSession().getUser().pegawai.namaLengkap
    + '&kdprofile=' + useUserSession().getProfile().id
    + "&token=" + useUserSession().token, "_blank");
}

export function printBladeLabPA(url: any): any {
  window.open(url ,"_blank");
}

export function printBladeSelf(url: any): any {
  // fetch(import.meta.env.VITE_API_BASE_URL + url, { /*method: 'POST'*/method: 'GET', headers: { 'token': useUserSession().token } })
  //   .then((response) => response.blob())
  //   .then((blob) => {
  //     const _url = window.URL.createObjectURL(blob);
  //     window.open(_url, '_blank').focus();
  //   }).catch((err) => {
  //     console.log(err);
  //   });
  window.open(import.meta.env.VITE_API_BASE_URL + url
    + "&user=" + useUserSession().getUser().pegawai.namaLengkap
    + '&kdprofile=' + useUserSession().getProfile().id
    + "&token=" + useUserSession().token, '_blank','toolbar=0,location=0,menubar=0,width=500');
}

export function namaPegawai(): any {
  return useUserSession().getUser().pegawai.namaLengkap
}
export function maxDate(): any {
  return new Date().toISOString().slice(0, 10)
}

export function alertCeklis(): any {
  return 'Ceklis data terlebih dahulu'
}
export function alertHapus(): any {
  return 'Yakin mau menghapus data?'
}
export function alertBatal(): any {
  return 'Yakin mau membatalkan data ini?'
}
export function alertPilih(): any {
  return 'Pilih data terlebih dahulu'
}
export function alertKasir(): any {
  return 'Tidak bisa mengubah data karena sudah diverif kasir, harap hubungi kasir terlebih dahulu!'
}
export function INITIALS(params: any): any {
  if(params == null){
    params = ''
  }
  let ini = params.split(' ')
  let init = params.substr(0, 1)
  if (ini.length > 1) {
    init = init + ini[1].substr(0, 1)
  }
  return init
}
export function formatRupiah(angka: any, prefix: any) {
  if (angka == undefined) return 0
  let minus = ''
  if (parseFloat(angka) < 0) {
    minus = '-'
    angka = angka *-1
  }
  let number_string = angka.toString().replace(/[^,\d]/g, ',').toString(),
  split = number_string.split(','),
  sisa = split[0].length % 3,
  rupiah = split[0].substr(0, sisa),
  ribuan = split[0].substr(sisa).match(/\d{3}/gi),
  separator = '';

  // tambahkan titik jika yang di input sudah menjadi angka ribuan
  if (ribuan) {
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
  }
  rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
  rupiah = minus+ rupiah
  return prefix == undefined || prefix == '' ? rupiah : prefix + ' ' + rupiah;
}
export function formatDateIndoSimpleMs(tgl: any): any {
  var date: any = new Date(tgl)
  var tahun = date.getFullYear().toString().substr(2, 2)
  var bulan = date.getMonth()
  var tanggal = date.getDate()
  var hari = date.getDay()
  var jam = date.getHours()
  var menit = date.getMinutes()
  var detik = date.getSeconds()
  switch (hari) {
    case 0:
      hari = 'Ming'
      break
    case 1:
      hari = 'Sen'
      break
    case 2:
      hari = 'Sel'
      break
    case 3:
      hari = 'Rab'
      break
    case 4:
      hari = 'Kam'
      break
    case 5:
      hari = 'Jum'
      break
    case 6:
      hari = 'Sab'
      break
  }
  switch (bulan) {
    case 0:
      bulan = 'Jan'
      break
    case 1:
      bulan = 'Feb'
      break
    case 2:
      bulan = 'Mar'
      break
    case 3:
      bulan = 'Apr'
      break
    case 4:
      bulan = 'Mei'
      break
    case 5:
      bulan = 'Jun'
      break
    case 6:
      bulan = 'Jul'
      break
    case 7:
      bulan = 'Agu'
      break
    case 8:
      bulan = 'Sep'
      break
    case 9:
      bulan = 'Okt'
      break
    case 10:
      bulan = 'Nov'
      break
    case 11:
      bulan = 'Des'
      break
  }
  return hari + ', ' + tanggal + ' ' + bulan + ' ' + tahun + ' ' + (jam.toString().length == 2 ? jam : "0" + jam) + ':'
    + (menit.toString().length == 2 ? menit : "0" + menit) + ':'
    + (detik.toString().length == 2 ? detik : "0" + detik)
}
export function formatTanggal(tanggal: any): any {
  const date = new Date(tanggal);
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();
  return `${day}-${month}-${year}`;
};


export function openFile(path: any): any {
  fetch(import.meta.env.VITE_API_BASE_URL + 'general/show-file?path=' + path, { /*method: 'POST'*/method: 'GET', headers: { 'token': useUserSession().token } })
    .then((response) => response.blob())
    .then((blob) => {
      const _url = window.URL.createObjectURL(blob);
      window.open(_url).focus();
    }).catch((err) => {
      console.log(err);
    });

}

export function open(url: any): any {
  fetch(import.meta.env.VITE_API_BASE_URL + url, { /*method: 'POST'*/method: 'GET', headers: { 'token': useUserSession().token } })
    .then((response) => response.blob())
    .then((blob) => {
      const _url = window.URL.createObjectURL(blob);
      window.open(_url).focus();
    }).catch((err) => {
      console.log(err);
    });

}

export function openInTab(url: any): any {
  fetch(import.meta.env.VITE_API_BASE_URL + url, { /*method: 'POST'*/method: 'GET', headers: { 'token': useUserSession().token } })
    .then((response) => response.blob())
    .then((blob) => {
      const _url = window.URL.createObjectURL(blob);
      window.open(_url, '_blank','toolbar=0,location=0,menubar=0');
    }).catch((err) => {
      console.log(err);
    });

}

export async function download(url: any, namafile: any): any {
  

  // const blobs: Blob = new Blob([import.meta.env.VITE_API_BASE_URL + url, { /*method: 'POST'*/method: 'GET', headers: { 'token': useUserSession().token } }], {
  //   type: 'application/pdf'
  // });
  // console.log(blobs)
  // const _url = window.URL.createObjectURL(blobs)
  // const link = document.createElement('a');
  //     link.href = _url;
  //     link.download = namafile + '.pdf';
  //     link.click();
  // fetch(import.meta.env.VITE_API_BASE_URL + url, { /*method: 'POST'*/method: 'GET', headers: { 'token': useUserSession().token }, type: 'cors' })
  //   .then((response) => response.blob({type: 'application/pdf'}))
  //   .then((blob) => {
  //     console.log(blob)
  //     console.log(response)
  //     const _url = window.URL.createObjectURL(blob);
  //     const link = document.createElement('a');
  //     link.href = _url;
  //     link.download = namafile + '.pdf';
  //     link.click();
  //   }).catch((err) => {
  //     console.log(err);
  //   });

  //  fetch(import.meta.env.VITE_API_BASE_URL + url, { /*method: 'POST'*/method: 'GET', headers: { 'token': useUserSession().token } })
  //   .then((response) => response.blob())
  //   .then((blob) => {
  //     console.log(blob)
  //     // if(blob.type == 'application/pdf'){
  //       const _url = window.URL.createObjectURL(blob);
  //       window.open(_url).focus();
  //     // } else{
  //     //   alert("warning" ,'Data Belum Di Grouping');
  //     //   return
  //     // }
     
  //   }).catch((err) => {
  //     console.log(err);
  //   });

  await fetch(import.meta.env.VITE_API_BASE_URL + url, { /*method: 'POST'*/method: 'GET', headers: { 'token': useUserSession().token } })
    .then((response) => response.blob())
    .then((blob) => {
      const _url = window.URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = _url;
      link.download = namafile + '.pdf';
      link.click();
    }).catch((err) => {
      console.log(err);
    });

}
export async function downloadRAR(url: any, namafile: any) {
    await fetch(import.meta.env.VITE_API_BASE_URL + url, { /*method: 'POST'*/method: 'GET', headers: { 'token': useUserSession().token } })
    .then((response) => response.blob())
    .then((blob) => {
      const _url = window.URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = _url;
      link.download = namafile;
      link.click();
    }).catch((err) => {
      console.log(err);
    });

}
export function setObjectPasien(pasien: any): any {
  return pasien;
}

export function setObjectRegistrasi(registrasi: any): any {
  return {
    'norec_apd': registrasi.norec_apd,
    'norec_pd': registrasi.norec_pd,
    'noregistrasi': registrasi.noregistrasi,
    'tglregistrasi': registrasi.tglregistrasi,
    'kelompokpasien': registrasi.kelompokpasien,
    'asalrujukan' : registrasi.asalrujukan,
    'namarekanan': registrasi.namarekanan,
    'namakelas': registrasi.namakelas,
    'objectruanganfk': registrasi.objectruanganfk,
    'tglpulang' : registrasi.tglpulang,
    'namaruangan': registrasi.namaruangan,
    'dokter': registrasi.dokter,
    'objectpegawaifk': registrasi.objectpegawaifk,
  }
}

export function emr(): any {
  [
    {
      "title": "2. Tanda Vital",
      "value": [
        {
          "subTitle": "Tekanan Darah",
          "model": "tekananDarah",
        },
        {
          "subTitle": "Frekuensi Nadi",
          "model": "frekuensiNadi",
        },
        {
          "subTitle": "Suhu",
          "model": "suhu",
        },
        {
          "subTitle": "Frekuensi Nafas",
          "model": "frekuensiNafas",
        },
        {
          "subTitle": "Skor Nyeri",
          "model": "skorNyeri",
        },
      ]
    },
    {
      "title": "3. Antropometri",
      "value": [
        {
          "subTitle": "Berat Badan",
          "model": "beratBadan",
        },
        {
          "subTitle": "Tinggi Badan",
          "model": "tinggiBadan",
        },
        {
          "subTitle": "Lingkar Perut",
          "model": "lingkarPerut",
        },
        {
          "subTitle": "IMT",
          "model": "imt",
        },
      ]
    },
  ]
}
export function unFormatRupiah(angka: any) {
  return parseFloat(angka.toString().replace(/\./g, '').replace(',', '.'));
}

export function tandaTangan() {
  return {
    get: function (element_id: any) {
      let sigCanvas: any = document.getElementById(element_id);
      if (sigCanvas) {
        const dataURL = sigCanvas.toDataURL();
        if (dataURL != 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAALQAAAC0CAYAAAA9zQYyAAAAAXNSR0IArs4c6QAAA1lJREFUeF7t0gENAAAIwzDu3zQ+luLgozunQKjAQltMUeCAhiBVAOjUO40BmoFUAaBT7zQGaAZSBYBOvdMYoBlIFQA69U5jgGYgVQDo1DuNAZqBVAGgU+80BmgGUgWATr3TGKAZSBUAOvVOY4BmIFUA6NQ7jQGagVQBoFPvNAZoBlIFgE690xigGUgVADr1TmOAZiBVAOjUO40BmoFUAaBT7zQGaAZSBYBOvdMYoBlIFQA69U5jgGYgVQDo1DuNAZqBVAGgU+80BmgGUgWATr3TGKAZSBUAOvVOY4BmIFUA6NQ7jQGagVQBoFPvNAZoBlIFgE690xigGUgVADr1TmOAZiBVAOjUO40BmoFUAaBT7zQGaAZSBYBOvdMYoBlIFQA69U5jgGYgVQDo1DuNAZqBVAGgU+80BmgGUgWATr3TGKAZSBUAOvVOY4BmIFUA6NQ7jQGagVQBoFPvNAZoBlIFgE690xigGUgVADr1TmOAZiBVAOjUO40BmoFUAaBT7zQGaAZSBYBOvdMYoBlIFQA69U5jgGYgVQDo1DuNAZqBVAGgU+80BmgGUgWATr3TGKAZSBUAOvVOY4BmIFUA6NQ7jQGagVQBoFPvNAZoBlIFgE690xigGUgVADr1TmOAZiBVAOjUO40BmoFUAaBT7zQGaAZSBYBOvdMYoBlIFQA69U5jgGYgVQDo1DuNAZqBVAGgU+80BmgGUgWATr3TGKAZSBUAOvVOY4BmIFUA6NQ7jQGagVQBoFPvNAZoBlIFgE690xigGUgVADr1TmOAZiBVAOjUO40BmoFUAaBT7zQGaAZSBYBOvdMYoBlIFQA69U5jgGYgVQDo1DuNAZqBVAGgU+80BmgGUgWATr3TGKAZSBUAOvVOY4BmIFUA6NQ7jQGagVQBoFPvNAZoBlIFgE690xigGUgVADr1TmOAZiBVAOjUO40BmoFUAaBT7zQGaAZSBYBOvdMYoBlIFQA69U5jgGYgVQDo1DuNAZqBVAGgU+80BmgGUgWATr3TGKAZSBUAOvVOY4BmIFUA6NQ7jQGagVQBoFPvNAZoBlIFgE690xigGUgVADr1TmOAZiBVAOjUO40BmoFUAaBT7zQGaAZSBYBOvdMYoBlIFQA69U5jgGYgVQDo1DuNeTzRALUFDh1xAAAAAElFTkSuQmCC')
          return dataURL
        else
          return null
      } else {
        return null
      }
    },

    set: function (element_id: any, value: any) {
      let sigCanvas: any = document.getElementById(element_id);
      if (sigCanvas) {
        let context = sigCanvas.getContext("2d");
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        let imagess = value
        let background = new Image();
        background.src = imagess
        background.onload = function () {
          context.drawImage(background, 0, 0, 150, 150);
        }
      }
    },

    clear: function (canvas: any) {
      var sigCanvas: any = document.getElementById(canvas)
      var context = sigCanvas.getContext("2d");
      context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    },
    
  }
}

export function pegawaiLogin(): any {
  return {
    id: useUserSession().getUser().pegawai.id,
    namalengkap: useUserSession().getUser().pegawai.namaLengkap
  }
}
export function getFileBE(path: any): any {
  return fetch(import.meta.env.VITE_API_BASE_URL + 'general/show-file?path=' + path, { /*method: 'POST'*/method: 'GET', headers: { 'token': useUserSession().token } })
    .then((response) => response.blob())
    .then((blob) => {
      return blob;

    }).catch((err) => {
      console.log(err);
    });

}
export function monthList(): any {
  let month = [
    "Januari", "Februari", "Maret",
    "April", "Mei", "Juni",
    "Juli", "Agustus", "September",
    "Oktober", "November", "Desember"
  ];
  let list = []
  for (let x = 0; x < month.length; x++) {
    const element = month[x];
    list.push({ kode: x, nama: element })
  }
  return list
}
export function yearList(): any {
  let currentYear = new Date().getFullYear();
  let startYear = currentYear - 5;
  let list = []
  for (var year = startYear; year <= currentYear; year++) {
    list.push({ kode: year, nama: year })
  }
  return list
}

export function hasilLAB_THEO(): any {
  return [
    {
      "norec": "06634479-864f-443c-8013-011d0218df91",
      "noorder": "L2307000009",
      "tglorder": "2023-07-11 10:47:38",
      "norec_op": "7518894a-3881-41a8-99be-f9551019e479",
      "namaproduk": "Glukosa sewaktu",
      "namalengkap": "AULIA RIZKI MAULANA",
      "objectdepartemenfk": 3,
      "tgloperasi": null,
      "estimasiwaktuoperasi": null,
      "keteranganlainnya": null,
      "status": "verifikasi",
      "color_status": "danger",
      "hasil": {
        "namaproduk": "Glukosa sewaktu",
        "detailpemeriksaan": "Glukosa sewaktu",
        "hasil": "98",
        "flag": "N",
        "noregistrasifk": "140d7b3d-2c8a-4a8e-9bff-8a1a0732f7a0",
        "produkdetaillabfk": 402,
        "produkfk": 1002123679,
        "nilaitext": "70 - 200",
        "satuanstandar": "mg / dl",
        "norec_pp": "4ac66fa9-bbd4-43ab-9306-f50c01ef0eee",
        "nilaimin": "70",
        "nilaimax": "200"
      },


    },
    {
      "norec": "06634479-864f-443c-8013-011d0218df91",
      "noorder": "L2307000009",
      "tglorder": "2023-07-11 10:47:38",
      "norec_op": "1254da89-b0b2-44f8-9128-9f1902369837",
      "namaproduk": "Ureum Darah",
      "namalengkap": "AULIA RIZKI MAULANA",
      "objectdepartemenfk": 3,
      "tgloperasi": null,
      "estimasiwaktuoperasi": null,
      "keteranganlainnya": null,
      "status": "verifikasi",
      "color_status": "danger",
      "hasil": {
        "namaproduk": "Ureum Darah",
        "detailpemeriksaan": "Ureum Darah",
        "hasil": "24,2",
        "flag": "N",
        "noregistrasifk": "140d7b3d-2c8a-4a8e-9bff-8a1a0732f7a0",
        "produkdetaillabfk": 401,
        "produkfk": 5011464,
        "nilaitext": "13,8 - 43,9",
        "satuanstandar": "mg / dl",
        "norec_pp": "b515bde3-cd9a-4f5b-9482-75c56664daaa",
        "nilaimin": "13",
        "nilaimax": "43"
      },
    },
    {
      "norec": "06634479-864f-443c-8013-011d0218df91",
      "noorder": "L2307000009",
      "tglorder": "2023-07-11 10:47:38",
      "norec_op": "3a2d89df-b09b-4877-bcc6-638471d1aa79",
      "namaproduk": "kreatinin",
      "namalengkap": "AULIA RIZKI MAULANA",
      "objectdepartemenfk": 3,
      "tgloperasi": null,
      "estimasiwaktuoperasi": null,
      "keteranganlainnya": null,
      "status": "verifikasi",
      "color_status": "danger",
      "hasil":
      {
        "namaproduk": "kreatinin",
        "detailpemeriksaan": "kreatinin",
        "hasil": "0,72",
        "flag": "R",
        "noregistrasifk": "140d7b3d-2c8a-4a8e-9bff-8a1a0732f7a0",
        "produkdetaillabfk": 403,
        "produkfk": 5011465,
        "nilaitext": "\t0,75 - 1,25",
        "satuanstandar": "mg / dl",
        "norec_pp": "7887aa20-b693-49de-bdb9-7815f5cede40",
        "nilaimin": "75",
        "nilaimax": "125"
      },
    },
    {
      "norec": "06634479-864f-443c-8013-011d0218df91",
      "noorder": "L2307000009",
      "tglorder": "2023-07-11 10:47:38",
      "norec_op": "8c5e56ff-d52b-40b3-8624-277a4f487113",
      "namaproduk": "Kolesterol total",
      "namalengkap": "AULIA RIZKI MAULANA",
      "objectdepartemenfk": 3,
      "tgloperasi": null,
      "estimasiwaktuoperasi": null,
      "keteranganlainnya": null,
      "status": "verifikasi",
      "color_status": "danger",
      "hasil": {
        "namaproduk": "Kolesterol total",
        "detailpemeriksaan": "Kolesterol total",
        "hasil": "265",
        "flag": "TN",
        "noregistrasifk": "140d7b3d-2c8a-4a8e-9bff-8a1a0732f7a0",
        "produkdetaillabfk": 406,
        "produkfk": 5011460,
        "nilaitext": "<200",
        "satuanstandar": "mg / dl",
        "norec_pp": "35f404b8-9d62-48bd-a230-ea6a8cb27252",
        "nilaimin": "0",
        "nilaimax": "200"
      },

    },
    {
      "norec": "06634479-864f-443c-8013-011d0218df91",
      "noorder": "L2307000009",
      "tglorder": "2023-07-11 10:47:38",
      "norec_op": "3c641290-ec93-4efd-af18-14101843fa26",
      "namaproduk": "Kolesterol LDL",
      "namalengkap": "AULIA RIZKI MAULANA",
      "objectdepartemenfk": 3,
      "tgloperasi": null,
      "estimasiwaktuoperasi": null,
      "keteranganlainnya": null,
      "status": "verifikasi",
      "color_status": "danger",
      "hasil": {
        "namaproduk": "Kolesterol LDL",
        "detailpemeriksaan": "Kolesterol LDL",
        "hasil": "163,4",
        "flag": "TN",
        "noregistrasifk": "140d7b3d-2c8a-4a8e-9bff-8a1a0732f7a0",
        "produkdetaillabfk": 404,
        "produkfk": 5011461,
        "nilaitext": "<=100",
        "satuanstandar": "mg / dl",
        "norec_pp": "dcd66ecd-5a56-4665-8d80-c4bff08bc5c8",
        "nilaimin": "0",
        "nilaimax": "100"
      },

    },
    {
      "norec": "06634479-864f-443c-8013-011d0218df91",
      "noorder": "L2307000009",
      "tglorder": "2023-07-11 10:47:38",
      "norec_op": "8517495c-4e8a-4278-9e34-0feac0f6984d",
      "namaproduk": "Kolesterol HDL",
      "namalengkap": "AULIA RIZKI MAULANA",
      "objectdepartemenfk": 3,
      "tgloperasi": null,
      "estimasiwaktuoperasi": null,
      "keteranganlainnya": null,
      "status": "verifikasi",
      "color_status": "danger",
      "hasil": {
        "namaproduk": "Kolesterol HDL",
        "detailpemeriksaan": "Kolesterol HDL",
        "hasil": "46",
        "flag": "N",
        "noregistrasifk": "140d7b3d-2c8a-4a8e-9bff-8a1a0732f7a0",
        "produkdetaillabfk": 405,
        "produkfk": 5011462,
        "nilaitext": ">=40",
        "satuanstandar": "mg / dl",
        "norec_pp": "bef5a9a9-b0f5-4b3a-81b9-5e442ad35061",
        "nilaimin": "40",
        "nilaimax": "1000"
      },
    },
    {
      "norec": "06634479-864f-443c-8013-011d0218df91",
      "noorder": "L2307000009",
      "tglorder": "2023-07-11 10:47:38",
      "norec_op": "e2d39160-d69f-4d74-b025-5b6dc18e3b1a",
      "namaproduk": "trigliserid",
      "namalengkap": "AULIA RIZKI MAULANA",
      "objectdepartemenfk": 3,
      "tgloperasi": null,
      "estimasiwaktuoperasi": null,
      "keteranganlainnya": null,
      "status": "verifikasi",
      "color_status": "danger",
      "hasil": {
        "namaproduk": "trigliserid",
        "detailpemeriksaan": "trigliserid",
        "hasil": "278",
        "flag": "TN",
        "noregistrasifk": "140d7b3d-2c8a-4a8e-9bff-8a1a0732f7a0",
        "produkdetaillabfk": 407,
        "produkfk": 5011463,
        "nilaitext": "<150",
        "satuanstandar": "mg / dl",
        "norec_pp": "f2bf59b3-1459-4bfc-b875-23432b3a95ca",
        "nilaimin": "0",
        "nilaimax": "150"
      }
    }
  ]
}
export function formatDateIndoSimpleDayNoTime(tgl: any): any {
  var date: any = new Date(tgl)
  var tahun = date.getFullYear().toString().substr(2, 2)
  var bulan = date.getMonth()
  var tanggal = date.getDate()
  var hari = date.getDay()
  var jam = date.getHours()
  var menit = date.getMinutes()
  var detik = date.getSeconds()
  switch (hari) {
    case 0:
      hari = 'Ming'
      break
    case 1:
      hari = 'Sen'
      break
    case 2:
      hari = 'Sel'
      break
    case 3:
      hari = 'Rab'
      break
    case 4:
      hari = 'Kam'
      break
    case 5:
      hari = 'Jum'
      break
    case 6:
      hari = 'Sab'
      break
  }
  switch (bulan) {
    case 0:
      bulan = 'Jan'
      break
    case 1:
      bulan = 'Feb'
      break
    case 2:
      bulan = 'Mar'
      break
    case 3:
      bulan = 'Apr'
      break
    case 4:
      bulan = 'Mei'
      break
    case 5:
      bulan = 'Jun'
      break
    case 6:
      bulan = 'Jul'
      break
    case 7:
      bulan = 'Agu'
      break
    case 8:
      bulan = 'Sep'
      break
    case 9:
      bulan = 'Okt'
      break
    case 10:
      bulan = 'Nov'
      break
    case 11:
      bulan = 'Des'
      break
  }
  return hari + ', ' + tanggal + ' ' + bulan + ' ' + tahun
}

export function daysDifference(start: string, end: string): any {
  // Define two date objects
  const date1:any = new Date(start);
  const date2:any = new Date(end);

  // Calculate the time difference in milliseconds
  const timeDifference = date2 - date1;

  // Convert the time difference to days
  let daysDifference = timeDifference / (1000 * 60 * 60 * 24);
  if(daysDifference <= 1){
    daysDifference = 1
  }
  return daysDifference
}

export const avatarText = (value: string) => {
  if(value == null){
    value = ''
  }
  const initials = value
  .split(" ")
  .map((word:any) => word.charAt(0).toUpperCase())
  .slice(0, 2) // Take only the first two characters
  .join("");

  return initials
}
export const sendSocket = async(name:any, body:any) => {

  socket.emit('get-server-socket', {
      "name": name,
      "body": body
  });
  body.method = 'save'
  await useApi().postNoMessage( 'general/store-notif', body)
}

export function mapLoginUserToRuangan(): any {
  return useUserSession().getUser().mapLoginUserToRuangan
}
export function kelompokUserId(): any {
  return useUserSession().getUser().kelompokUser.id
}

export function exportExcel(data:any,fileName:any): any {

  const worksheet = XLSX.utils.json_to_sheet(data)
  const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

  let EXCEL_TYPE  :any= 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION :any= '.xlsx';
  const blobs: Blob = new Blob([excelBuffer], {
      type: EXCEL_TYPE
  });
  const _url = window.URL.createObjectURL(blobs)

  window.open(_url, EXCEL_EXTENSION).focus()
  exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);

}
export function saveAsExcelFile(buffer:any,fileName:any): any {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
      type: EXCEL_TYPE
  });

  const desiredFileName = fileName + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);

}

export function namaProfile(): any {
  return useUserSession().getProfile().namaprofile
}

export function roundToDecimal(number, decimalPlaces) {
    return Number(number.toFixed(decimalPlaces));
}
export function terbilang(number: number):any{
    const x: number = Math.abs(number);
    const angka: string[] = [
        "", "satu", "dua", "tiga", "empat", "lima",
        "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"
    ];
    let temp: string = "";

    if (number < 12) {
        temp = " " + angka[number];
    } else if (number < 20) {
        temp = this.terbilang(number - 10) + " belas";
    } else if (number < 100) {
        temp = this.terbilang(Math.floor(number / 10)) + " puluh" + this.terbilang(number % 10);
    } else if (number < 200) {
        temp = " seratus" + this.terbilang(number - 100);
    } else if (number < 1000) {
        temp = this.terbilang(Math.floor(number / 100)) + " ratus" + this.terbilang(number % 100);
    } else if (number < 2000) {
        temp = " seribu" + this.terbilang(number - 1000);
    } else if (number < 1000000) {
        temp = this.terbilang(Math.floor(number / 1000)) + " ribu" + this.terbilang(number % 1000);
    } else if (number < 1000000000) {
        temp = this.terbilang(Math.floor(number / 1000000)) + " juta" + this.terbilang(number % 1000000);
    } else if (number < 1000000000000) {
        temp = this.terbilang(Math.floor(number / 1000000000)) + " milyar" + this.terbilang(number % 1000000000);
    } else if (number < 1000000000000000) {
        temp = this.terbilang(Math.floor(number / 1000000000000)) + " trilyun" + this.terbilang(number % 1000000000000);
    }

    return temp.toUpperCase();
}
export function countBirthday (birthday: Date, dataNow: Date) :any {
  const diffInMilliseconds = Math.abs(dataNow.getTime() - birthday.getTime());
  const years = Math.floor(diffInMilliseconds / (365.25 * 24 * 60 * 60 * 1000));
  const months = Math.floor((diffInMilliseconds % (365.25 * 24 * 60 * 60 * 1000)) / (30.44 * 24 * 60 * 60 * 1000));
  const days = Math.floor((diffInMilliseconds % (30.44 * 24 * 60 * 60 * 1000)) / (24 * 60 * 60 * 1000));
  return {
    years,
    months,
    days
  };
};


export function cacheEMR() {

  return {
    get(name: any) {
      var temp = window.localStorage.getItem('cacheTAB_');
      if (temp !== undefined && temp !== null && maps.length === 0)
        mapsCache = JSON.parse(temp);

      for (var key in mapsCache) {
        if (mapsCache.hasOwnProperty(key)) {
          var element = mapsCache[key];
          if (element.name === name)
            return element.value;
        }
      }
      return undefined;
    },
    set(name: any, value: any) {
      var item = undefined;
      for (var key in mapsCache) {
        if (mapsCache.hasOwnProperty(key)) {
          var element = mapsCache[key];
          if (element.name === name) {
            item = element;
            mapsCache[key] = {
              name: name,
              value: value
            };
            break;
          }
        }
      }
      if (item === undefined)
        mapsCache.push({
          name: name,
          value: value
        });
      else item = {
        name: name,
        value: value
      };
      window.localStorage.setItem('cacheTAB_', JSON.stringify(mapsCache));
    }
  }
}
export async function statusClosingPasien(key: any): any {
  // key = norec_pd / noregistasi
  const response = await useApi().get(`general/get-status-close?key=${key}`);
  let closingPelayanaData = {
    key: key,
    status: response.status,
  };
  cacheHelper().set('status_closing', closingPelayanaData);
  if (response.status == true) {
    alert("warning" ,'Pelayanan yang sudah di Closing tidak bisa di ubah !');
    throw "exit";
  }
  return;
}
export function pagination(): any {
  return {
     typeLarge : [3,5,6,10,15,25,50,100,200,500,1000,'all'],
     typeSmall: [1, 5, 10, 15, 25, 50],
     typeMedium: [5, 10, 20, 30, 40, 50,60],
     typeGenaral: [3, 6, 9, 15, 30, 60],
     defaultLarge : 25,
     defaultSmall : 5,
     defaultMedium: 10,
     defaultGeneral: 9

  }
}
export function dateTimeFormat(): any{
  return {
    prime: {
      dateTime : 'dd/mm/yy hh:mm:ss',
      date : 'dd/mm/yy',
    },
    datepic: {
      dateTime : 'DD-MM-YYYY hh:mm:ss',
      date : 'DD-MM-YYYY',
    },
    genaral: {
      dateTime : 'DD-MM-YYYY hh:mm:ss',
      date : 'DD-MM-YYYY',
    }
  }
}

export function cacheInput() {

  return {
    get(name: any) {
      var temp = window.sessionStorage.getItem('cacheInput_');
      if (temp !== undefined && temp !== null && maps.length === 0)
        mapsCache = JSON.parse(temp);

      for (var key in mapsCache) {
        if (mapsCache.hasOwnProperty(key)) {
          var element = mapsCache[key];
          if (element.name === name)
            return element.value;
        }
      }
      return undefined;
    },
    set(name: any, value: any) {
      var item = undefined;
      for (var key in mapsCache) {
        if (mapsCache.hasOwnProperty(key)) {
          var element = mapsCache[key];
          if (element.name === name) {
            item = element;
            mapsCache[key] = {
              name: name,
              value: value
            };
            break;
          }
        }
      }
      if (item === undefined)
        mapsCache.push({
          name: name,
          value: value
        });
      else item = {
        name: name,
        value: value
      };
      window.sessionStorage.setItem('cacheInput_', JSON.stringify(mapsCache));
    }
  }
}
export function EMR(): any{
  return {
   city : "Bandung"
  }
}
export function convertJSONtreeEgie (response):any {
  var keys = Object.entries(response)
  var datas = []
  for (let x = 0; x < keys.length; x++) {
      const element = keys[x];
      if( element[1] != null &&  typeof element[1] === 'object'  ){
          var keys2 = Object.entries(element[1])
          var child = []
          for (let y = 0; y < keys2.length; y++) {
              const element2 = keys2[y];
              if( element2[1] != null &&  typeof element2[1] === 'object'  ){
                  var keys22 = Object.entries(element2[1])
                  var childx = []
                  for (let y = 0; y < keys22.length; y++) {
                      const element22 = keys22[y];
                      childx.push({data: element22[0], label: element22[0] + ' : ' + (element22[1] != null ? element22[1] : ''),  "expandedIcon": "pi pi-folder-open",
                      "collapsedIcon": "pi pi-folder",expanded:true })
                  }
                  child.push({data: element2[0], label: element2[0],  "expandedIcon": "pi pi-folder-open",
                  "collapsedIcon": "pi pi-folder",expanded:true, children : childx})
              }else{
                  child.push({data: element2[0], label: element2[0] + ' : ' + (element2[1] != null ? element2[1] : ''),  "expandedIcon": "pi pi-folder-open",
                  "collapsedIcon": "pi pi-folder", expanded:true})
              }
          }
          datas.push({data: element[0], label: element[0] ,  "expandedIcon": "pi pi-folder-open",
          "collapsedIcon": "pi pi-folder",expanded:true, children : child})
      }else{
          datas.push({data: element[0], label: element[0] + ' : ' +  (element[1] != null ? element[1] : '') ,  "expandedIcon": "pi pi-folder-open",
          "collapsedIcon": "pi pi-folder",expanded:true})
      }
  }
  return datas;
}
export function convertJSONtree(data :any) {
  var keys = Object.entries(data);
  var datas = [];

  for (let x = 0; x < keys.length; x++) {
      const element = keys[x];

      if (element[1] != null && typeof element[1] === 'object') {
          var child:any = convertJSONtree(element[1]);
          datas.push({
              key: element[0],
              data: element[0],
              label: element[0],
              icon: "pi pi-fw pi-folder",
              expanded: true,
              children: child
          });
      } else {
          datas.push({
              key: element[0],
              data: element[0],
              label: element[0] + ' : ' + (element[1] != null ? element[1] : ''),
              icon: "pi pi-fw pi-folder",
              expanded: true
          });
      }
  }
  return datas;
}

export function checkAksesEMR(e:any,kelompokUser:any,kelompokUserID:any,pegawaiId:any){

  let tglBerakhir = new Date(e.tglberakhir)
  let tglSekarang = new Date()
  let listKelompok = e.objectkelompokuserfk == null ? '' : e.objectkelompokuserfk
  let sourceKU = listKelompok != '' ? listKelompok.split(",") : []

  if(kelompokUser == 'rekam-medis' || e.tglberakhir == null){
    return true
    console.log('aa')
  }

  if(tglBerakhir <= tglSekarang){
    // H.alert('error','EMR telah terkunci, Silahkan minta akses peminjaman EMR')
    // throw 'exit'
    return true
  }else{
    console.log('cc')
    if(e.pegawaipemohonfk == null && listKelompok == ''){
      return true
    }
    if(sourceKU.length == 0){
      if(e.pegawaipemohonfk != pegawaiId){
         H.alert('error','EMR telah terkunci, Silahkan minta akses peminjaman EMR')
         throw 'exit'
      }else{
        console.log('ss')
        return true
      }
    }else{
      sourceKU.forEach(element => {
        if(element != kelompokUserID){
          // H.alert('error','EMR telah terkunci, Silahkan minta akses peminjaman EMR')
          // throw 'exit'
          return true
        }else{
          console.log('mm')
          return true
        }
      });
    }
  }

  return
}

export async function fetchDokter(filter: any = {}) {
  let query = '';
  if(filter && filter.query) query = filter.query;
  let returnedValue = [];
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    returnedValue = response;
  })

  return returnedValue;
}

export async function decryptBPJS(res: string): Promise<any>{
  const [response, hash] = atob(res).split('::');

  function verify(str: string): string {
      // cb bytes 32 with CRC32
      let crc = 0xffffffff; // Harus dimulai dengan 0xffffffff cenah
      for (let i = 0; i < str.length; i++) {
          crc = (crc >>> 8) ^ table[(crc ^ str.charCodeAt(i)) & 0xff];
      }
      return ((crc ^ 0xffffffff) >>> 0).toString();
  }

  const table = new Uint32Array(256).map((_, i) => {
      let c = i;
      for (let j = 0; j < 8; j++) {
          c = c & 1 ? 0xedb88320 ^ (c >>> 1) : c >>> 1;
      }
      return c;
  });

  const result = verify(response);

  if (result === hash) {
      return JSON.parse(response);
  } 
}