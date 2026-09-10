import { check } from 'k6'
import { generateToken, getPengeluaranKasirRev } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPengeluaranKasirRev({ token: data.token })
  check(res, {
    'kasir/daftar-pengeluaran is status 200': r => r.status === 200
  })
}
