import { generateToken } from '../../src/api.js'
import cekSatuSehatNumber from './cek-satu-sehat-number.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  cekSatuSehatNumber({ token: data.token })
}
