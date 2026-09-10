import { check } from 'k6'
import { generateToken, getPenerimaanHarian } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPenerimaanHarian({ token: data.token })
  check(res, {
    'report/kasir/laporan-penerimaan-harian is status 200': r => r.status === 200
  })
}
