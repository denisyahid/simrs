import { check } from 'k6'
import { generateToken, getDetailOrderBedah } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDetailOrderBedah({ token: data.token })
  check(res, {
    'bedah/detail-order is status 200': r => r.status === 200
  })
}
