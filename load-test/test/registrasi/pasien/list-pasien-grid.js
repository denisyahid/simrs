import { check } from 'k6'
import { generateToken, listPasienGrid } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = listPasienGrid({ token: data.token })
  check(res, {
    'registrasi/list-pasien-grid is status 200': r => r.status === 200
  })
}
