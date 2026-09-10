import { check } from 'k6'
import { generateToken, getStokRuangan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getStokRuangan({}, { token: data.token })
  check(res, {
    'get-stok-ruangan is status 200': r => r.status === 200
  })
}
