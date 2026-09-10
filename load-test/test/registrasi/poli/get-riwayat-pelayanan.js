import { check } from 'k6'
import { generateToken, getRiwayatPelayanan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getRiwayatPelayanan({ token: data.token })
  check(res, {
    'emr/detail-pelayanan is status 200': r => r.status === 200
  })
}
