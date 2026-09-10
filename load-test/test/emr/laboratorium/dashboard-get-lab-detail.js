import { check } from 'k6'
import { generateToken, getLabDetail } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getLabDetail({ token: data.token })
  check(res, {
    'dashboard/lab-detail is status 200': r => r.status === 200
  })
}
