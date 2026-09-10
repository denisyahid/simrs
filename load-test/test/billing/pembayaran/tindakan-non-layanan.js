import { check } from 'k6'
import { generateToken, getTindakanNonLayanan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getTindakanNonLayanan({ token: data.token })
  check(res, {
    'kasir/tagihan-non-layanan/pelayanan is status 200': r => r.status === 200
  })
}
