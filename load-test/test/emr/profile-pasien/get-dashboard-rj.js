import { check } from 'k6'
import { generateToken, getDashboardRJ } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDashboardRJ({}, { token: data.token })
  check(res, {
    'get-dashboard-rawat-jalan is status 200': r => r.status === 200
  })
}
