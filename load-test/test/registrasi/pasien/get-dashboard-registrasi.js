import { check } from 'k6'
import { generateToken, getDashboardRegistrasi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDashboardRegistrasi({ token: data.token })
  check(res, {
    'dashboard/registrasi is status 200': r => r.status === 200
  })
}
