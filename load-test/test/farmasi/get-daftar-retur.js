import { check } from 'k6'
import { generateToken, getDaftarRetur } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDaftarRetur( { token: data.token })
  check(res, {
    'farmasi/daftar-retur-obat-alkes is status 200': r => r.status === 200
  })
}
