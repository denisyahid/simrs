import { check } from 'k6'
import {
  generateToken,
  getKomponenHarga,
  getPegawayByJenisPetugas
} from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPegawayByJenisPetugas(
    { idJenisPetugas: '4' },
    { token: data.token }
  )
  check(res, {
    'get-pegawai-by-jenis-petugas is status 200': r => r.status === 200
  })
}
