import { check } from 'k6'
import { generateToken, getKelompokPasien } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getKelompokPasien({ token: data.token })
  check(res, {
    'registrasi/list-kelompokpasien-all is status 200': r => r.status === 200
  })
}
