import { check } from 'k6'
import { generateToken, listAnggaran } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = listAnggaran({}, { token: data.token })
  check(res, {
    'get-list-anggaran is status 200': r => r.status === 200
  })
}
