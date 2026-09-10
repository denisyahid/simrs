import { check } from 'k6'
import { generateToken, getPaguLayanan } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPaguLayanan({}, { token: data.token })
  check(res, {
    'get-pagu-layanan is status 200': r => r.status === 200
  })
}
