import { check } from 'k6'
import { generateToken, getDashboardEResep } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDashboardEResep( { token: data.token })
  check(res, {
    'dashboard/apotik is status 200': r => r.status === 200
  })
}
