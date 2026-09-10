import { check } from 'k6'
import { generateToken, getListTindakan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getListTindakan({ token: data.token })
  check(res, {
    'tindakan/list-tindakan is status 200': r => r.status === 200
  })
}
