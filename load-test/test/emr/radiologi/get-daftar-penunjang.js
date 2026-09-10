import { check } from 'k6'
import { generateToken, getPenunjang } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPenunjang({ token: data.token })
  check(res, {
    'dashboard/radiologi/get-penunjang-rad is status 200': r => r.status === 200
  })
}
