import { check } from 'k6'
import { generateToken, getStatusCloseLab } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getStatusCloseLab({ token: data.token })
  check(res, {
    'general/get-status-close is status 200': r => r.status === 200
  })
}
