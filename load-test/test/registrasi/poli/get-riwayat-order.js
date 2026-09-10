import { check } from 'k6'
import { generateToken, getRiwayatOrder } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getRiwayatOrder({ token: data.token })
  check(res, {
    'laboratorium/riwayat-order is status 200': r => r.status === 200
  })
}
