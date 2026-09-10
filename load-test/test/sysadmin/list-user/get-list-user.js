import { check } from 'k6'
import { generateToken, getListUser } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getListUser({}, { token: data.token })
  check(res, {
    'get-list-user is status 200': r => r.status === 200
  })
}
