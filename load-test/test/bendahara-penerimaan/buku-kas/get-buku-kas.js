import { check } from 'k6'
import { generateToken, getBukuKas } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getBukuKas({}, { token: data.token })
  check(res, {
    'get-buku-kas is status 200': r => r.status === 200
  })
}
