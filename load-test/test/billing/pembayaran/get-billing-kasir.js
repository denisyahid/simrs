import { check } from 'k6'
import { generateToken, getBillingKasir } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getBillingKasir({ token: data.token })
  check(res, {
    'kasir/billing is status 200': r => r.status === 200
  })
}
