import { check } from 'k6'
import { generateToken, getDetailReservasi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDetailReservasi({ token: data.token })
  check(res, {
    'reservasionline/get-history is status 200': r => r.status === 200
  })
}
