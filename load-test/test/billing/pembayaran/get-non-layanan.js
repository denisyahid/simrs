import { check } from 'k6'
import { generateToken, getNonLayanan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getNonLayanan({ token: data.token })
  check(res, {
    'kasir/daftar-tagihan-non-layanan is status 200': r => r.status === 200
  })
}
