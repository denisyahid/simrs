import { generateToken } from '../../../src/api.js'
import getComboTindakan from './get-combo-tindakan.js'
import getKomponenHarga from './get-komponen-harga.js'
import getPegawaiByJenisPetugas from './get-pegawai-by-jenis-petugas.js'
import getTindakan from './get-tindakan.js'
import saveTindakan from './save-tindakan.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getComboTindakan({ token: data.token })
  // getTindakan({ token: data.token })
  getKomponenHarga({ token: data.token })
  // getPegawaiByJenisPetugas({ token: data.token })
  saveTindakan({ token: data.token })
}
