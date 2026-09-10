import { check } from 'k6'
import { generateToken, getPenerimaanKasirRev } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPenerimaanKasirRev({ token: data.token })
  check(res, {
    'kasir/daftar-penerimaan is status 200': r => r.status === 200
  })
}
