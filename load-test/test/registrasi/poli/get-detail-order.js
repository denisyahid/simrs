import { check } from 'k6'
import { generateToken, getDetailOrder } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDetailOrder({ token: data.token })
  check(res, {
    'laboratorium/detail-order is status 200': r => r.status === 200
  })
}
