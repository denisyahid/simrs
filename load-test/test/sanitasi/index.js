import { generateToken } from '../../src/api.js'
import listPermohonanSanitasi from './list-permohonan-sanitasi.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  listPermohonanSanitasi({ token: data.token })
}
