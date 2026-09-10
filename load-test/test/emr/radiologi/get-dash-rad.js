import { check } from 'k6'
import { generateToken, getDashRad } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDashRad({ token: data.token })
  check(res, {
    'dashboard/radiologi is status 200': r => r.status === 200
  })
}
