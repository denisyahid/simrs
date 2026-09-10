import { check } from 'k6'
import { neraca, generateToken } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = neraca({}, { token: data.token })
  check(res, {
    'get-neraca is status 200': r => r.status === 200
  })
}
