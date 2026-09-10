import { check } from 'k6'
import { generateToken, getPasienByNoCM } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPasienByNoCM(
    { noCm: data.id || '98821811-6065-40ef-a724-cd8004d16512' },
    { token: data.token }
  )
  check(res, {
    'get-pasien-by-no-cm is status 200': r => r.status === 200
  })
}
