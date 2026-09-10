import { generateToken } from '../../src/api.js'
import daftarRemunPegawai from './daftar-remun-pegawai.js'
import getJasaPelayanan from './get-jasa-pelayanan.js'
import getPaguLayanan from './get-pagu-layanan.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getJasaPelayanan({ token: data.token })
  getPaguLayanan({ token: data.token })
  daftarRemunPegawai({ token: data.token })
}
