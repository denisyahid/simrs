import { check } from 'k6'
import { generateToken, getDetailPelayan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDetailPelayan({ token: data.token })
  check(res, {
    'dashboard/radiologi/get-pelayanan is status 200': r => r.status === 200
  })
}
