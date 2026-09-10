import { check } from 'k6'
import { generateToken, DashboardRegistrasi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = DashboardRegistrasi({ token: data.token })
  check(res, {
    'get-combo-address is status 200': r => r.status === 200
  })
}
