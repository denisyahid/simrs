import { check } from 'k6'
import { generateToken, getPasienLamaReg } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPasienLamaReg({ token: data.token })
  check(res, {
    'registrasi/pasien-lama is status 200': r => r.status === 200
  })
}
