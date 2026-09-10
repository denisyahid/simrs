import { generateToken } from '../../../src/api.js'
import daftarDistribusi from './daftar-distribusi.js'
import getDropdownKirim from './get-dropdown-kirim.js'
import infoStok from './info-stok.js'
import saveDistribusi from './save-distribusi.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getDropdownKirim({ token: data.token })
  infoStok({ token: data.token })
  daftarDistribusi({ token: data.token })
  saveDistribusi({ token: data.token })
}
