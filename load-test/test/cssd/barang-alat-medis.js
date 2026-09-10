import { check } from 'k6'
import { generateToken, barangAlatMedis } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = barangAlatMedis({}, { token: data.token })
  check(res, {
    'get-barang-alat-medis is status 200': r => r.status === 200
  })
}
