import { check } from 'k6'
import { generateToken, getKelasRuangan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getKelasRuangan({ token: data.token })
  check(res, {
    'registrasi/kelas-by-ruangan is status 200': r => r.status === 200
  })
}
