import { check } from 'k6'
import { generateToken, getAsalRujukan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getAsalRujukan({ token: data.token })
  check(res, {
    'registrasi/list-asalrujukan-pasien is status 200': r => r.status === 200
  })
}
