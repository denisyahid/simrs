import { check } from 'k6'
import { generateToken, getPPK } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPPK({ token: data.token })
  check(res, {
    'general/ppk-bpjs is status 200': r => r.status === 200
  })
}
