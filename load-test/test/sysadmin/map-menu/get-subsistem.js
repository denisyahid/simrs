import { check } from 'k6'
import { generateToken, getSubSistem } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getSubSistem({}, { token: data.token })
  check(res, {
    'get-subsistem is status 200': r => r.status === 200
  })
}
