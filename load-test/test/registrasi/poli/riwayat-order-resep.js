import { check } from 'k6'
import { generateToken, getRiwayatOrderResep } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getRiwayatOrderResep({ token: data.token })
  check(res, {
    'farmasi/riwayat-order-resep is status 200': r => r.status === 200
  })
}
