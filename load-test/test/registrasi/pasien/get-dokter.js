import { check } from 'k6'
import { generateToken, getDokter } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDokter({ token: data.token })
  check(res, {
    'registrasi/dokter-paging is status 200': r => r.status === 200
  })
}
