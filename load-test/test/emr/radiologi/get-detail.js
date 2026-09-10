import { check } from 'k6'
import { generateToken, getDetail } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDetail({ token: data.token })
  check(res, {
    'dashboard/radiologi/get-detail-rad is status 200': r => r.status === 200
  })
}
