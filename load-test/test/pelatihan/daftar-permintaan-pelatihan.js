import { check } from 'k6'
import { generateToken, daftarPermintaanPelatihan } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = daftarPermintaanPelatihan({}, { token: data.token })
  check(res, {
    'get-daftar-permintaan-pelatihan is status 200': r => r.status === 200
  })
}
