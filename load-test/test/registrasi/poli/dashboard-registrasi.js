import { check } from 'k6'
import { generateToken, getDashboard } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDashboard({ token: data.token })
  check(res, {
    'dashboard/rawat-jalan-pasien is status 200': r => r.status === 200
  })
}
