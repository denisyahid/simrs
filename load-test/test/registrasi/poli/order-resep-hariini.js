import { check } from 'k6'
import { generateToken, getOrderResepHariIni } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getOrderResepHariIni({ token: data.token })
  check(res, {
    'farmasi/data-order-resep-hari-ini is status 200': r => r.status === 200
  })
}
