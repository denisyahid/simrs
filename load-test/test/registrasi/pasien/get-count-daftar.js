import { check } from 'k6'
import { generateToken, getCountDaftar } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getCountDaftar({ token: data.token })
  check(res, {
    'registrasi/count-daftar is status 200': r => r.status === 200
  })
}
