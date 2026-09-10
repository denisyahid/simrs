import { generateToken } from '../../../src/api.js'
// import getRiwayatResep from './get-riwayat-resep.js'
import getDropdownResepEmr from './get-dropdown.js'
import getDetailOrderResep from './get-detail-order-resep.js'
import getHargaObat from './get-harga-obat.js'
import saveOrderResep from './save-order-resep.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getDropdownResepEmr({ token: data.token })
  // getRiwayatResep({ token: data.token })
  getDetailOrderResep({ token: data.token })
  getHargaObat({ token: data.token })
  saveOrderResep({ token: data.token })
}
