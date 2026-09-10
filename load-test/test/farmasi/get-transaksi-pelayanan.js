import { check } from 'k6'
import { generateToken, getTransaksiPelayanan } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getTransaksiPelayanan( { token: data.token })
  check(res, {
    'farmasi/transaksi-pelayanan-farmasi is status 200': r => r.status === 200
  })
}
