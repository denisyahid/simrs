import { check } from 'k6'
import { generateToken, getLaporanTindakan } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getLaporanTindakan( { token: data.token })
  check(res, {
    'dashboard/laporan-tindakan-operasi is status 200': r => r.status === 200
  })
}
