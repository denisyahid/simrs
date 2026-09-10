import { check } from 'k6'
import { generateToken, getHeaderPasien } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getHeaderPasien({ token: data.token })
  check(res, {
    'emr/header-pasien is status 200': r => r.status === 200
  })
}
