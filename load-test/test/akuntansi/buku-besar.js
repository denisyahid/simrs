import { check } from 'k6'
import { bukuBesar, generateToken } from '../../src/api.js'


export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = bukuBesar({}, { token: data.token })
  check(res, {
    'get-buku-besar is status 200': r => r.status === 200
  })
}
