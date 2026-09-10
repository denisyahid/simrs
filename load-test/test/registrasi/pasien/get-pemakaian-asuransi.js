import { check } from 'k6'
import { generateToken, getPemakaianAsuransi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPemakaianAsuransi({ token: data.token })
  check(res, {
    'registrasi/pemakaian-asuransi is status 200': r => r.status === 200
  })
}
