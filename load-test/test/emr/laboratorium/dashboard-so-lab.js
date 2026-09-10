import { check } from 'k6'
import { generateToken, getDashboardSOLab } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDashboardSOLab({ token: data.token })
  check(res, {
    'dashboard/so-lab is status 200': r => r.status === 200
  })
}
