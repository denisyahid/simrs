import { check } from 'k6'
import { generateToken, getTotalBilling } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getTotalBilling({ token: data.token })
  check(res, {
    'emr/total-biliing is status 200': r => r.status === 200
  })
}
