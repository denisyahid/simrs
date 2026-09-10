import { check } from 'k6'
import { generateToken, getListRuangan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getListRuangan({ token: data.token })
  check(res, {
    'registrasi/list-ruangan-ri-rj is status 200': r => r.status === 200
  })
}
