import { check } from 'k6'
import { generateToken, getListRuanganRajal } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getListRuanganRajal({ token: data.token })
  check(res, {
    'registrasi/list-ruangan-rawat-jalan is status 200': r => r.status === 200
  })
}
