import { check } from 'k6'
import { generateToken, getDoubleRegis } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDoubleRegis({ token: data.token })
  check(res, {
    'registrasi/pasien-hari-ini is status 200': r => r.status === 200
  })
}
