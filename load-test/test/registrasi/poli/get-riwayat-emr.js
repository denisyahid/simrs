import { check } from 'k6'
import { generateToken, RiwayatEMR } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = RiwayatEMR({ token: data.token })
  check(res, {
    'get-combo-kecamatan is status 200': r => r.status === 200
  })
}
