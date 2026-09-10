import { check } from 'k6'
import { generateToken, getMenu } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getMenu({}, { token: data.token })
  check(res, {
    'get-menu is status 200': r => r.status === 200
  })
}
