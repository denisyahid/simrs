import { check } from 'k6'
import { generateToken, getDashboardReservasi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDashboardReservasi({ token: data.token })
  check(res, {
    'dashboard/rawat-jalan-reservasi is status 200': r => r.status === 200
  })
}
