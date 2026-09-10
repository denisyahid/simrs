import { check } from 'k6'
import { generateToken, listPermohonanSanitasi } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = listPermohonanSanitasi({}, { token: data.token })
  check(res, {
    'get-permohonan-sanitasi is status 200': r => r.status === 200
  })
}
