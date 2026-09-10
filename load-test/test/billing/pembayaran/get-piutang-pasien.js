import { check } from 'k6'
import { generateToken, getPiutangPasien } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPiutangPasien({ token: data.token })
  check(res, {
    'kasir/daftar-piutang-pasien is status 200': r => r.status === 200
  })
}
