import { check } from 'k6'
import { generateToken, getDaftarBarangAset } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDaftarBarangAset({}, { token: data.token })
  check(res, {
    'get-daftar-barang-aset is status 200': r => r.status === 200
  })
}
