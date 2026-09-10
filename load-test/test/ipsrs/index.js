import { generateToken } from '../../src/api.js'
import getPermintaanPerbaikan from './get-permintaan-perbaikan.js'
import savePermintaanPerbaikan from './save-permintaan-perbaikan.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getPermintaanPerbaikan({ token: data.token })
  savePermintaanPerbaikan({ token: data.token })
}
