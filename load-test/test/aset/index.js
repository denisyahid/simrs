import { generateToken } from '../../src/api.js'
import getDaftarBarangAset from './get-daftar-barang-aset.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getDaftarBarangAset({ token: data.token })
}
