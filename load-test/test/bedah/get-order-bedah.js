import { check } from 'k6'
import { generateToken, getOrderBedah } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getOrderBedah( { token: data.token })
  check(res, {
    'dashboard/get-order-bedah is status 200': r => r.status === 200
  })
}
