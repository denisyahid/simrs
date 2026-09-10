import { check } from 'k6'
import { generateToken, getDokterRadiologi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDokterRadiologi({ token: data.token })
  check(res, {
    'dashboard/radiologi/get-dokter is status 200': r => r.status === 200
  })
}
