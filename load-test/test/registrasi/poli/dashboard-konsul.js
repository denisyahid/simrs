import { check } from 'k6'
import { generateToken, getDashboardKonsul } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDashboardKonsul({ token: data.token })
  check(res, {
    'dashboard/get-jumlah-konsul is status 200': r => r.status === 200
  })
}
