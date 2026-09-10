import { check } from 'k6'
import { generateToken, getPermintaanPerbaikan } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPermintaanPerbaikan({}, { token: data.token })
  check(res, {
    'get-permintaan-perbaikan is status 200': r => r.status === 200
  })
}
