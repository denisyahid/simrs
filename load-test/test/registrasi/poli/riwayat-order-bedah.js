import { check } from 'k6'
import { generateToken, getRiwayatOrderBedah } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getRiwayatOrderBedah({ token: data.token })
  check(res, {
    'bedah/riwayat-order is status 200': r => r.status === 200
  })
}
