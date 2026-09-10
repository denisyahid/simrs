import { generateToken } from '../../src/api.js'
import daftarPermintaanPelatihan from './daftar-permintaan-pelatihan.js'
import rekapKehadiranPelatihan from './rekap-kehadiran-pelatihan.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  daftarPermintaanPelatihan({ token: data.token })
  rekapKehadiranPelatihan({ token: data.token })
}
