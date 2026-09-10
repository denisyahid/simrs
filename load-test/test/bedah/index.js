import { generateToken } from '../../src/api.js'
//import saveBatalVerifikasi from './save-batal-verifikasi.js'
import getOrderBedah from './get-order-bedah.js'
import getJadwalOperasi from './get-jadwal-operasi.js'
import getLaporanTindakan from './get-laporan-tindakan.js'
import getTanggalOperasi from './get-tgl-operasi.js'
import saveOrder from './save-order.js'


export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  //saveBatalVerifikasi({ token: data.token })

  getOrderBedah({ token: data.token })
  getJadwalOperasi({ token: data.token })
  getLaporanTindakan({ token: data.token })
  getTanggalOperasi({ token: data.token })
  saveOrder({ token: data.token })
}
