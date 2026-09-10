import { check } from 'k6'
import { generateToken, getKwitansiRI } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getKwitansiRI({ token: data.token })
  check(res, {
    'kasir/daftar-penerimaan/report/kwitansi-ranap-wna is status 200': r => r.status === 200
  })
}
