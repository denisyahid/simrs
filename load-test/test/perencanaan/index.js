import { generateToken } from '../../src/api.js'
import listAnggaran from './list-anggaran.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  listAnggaran({ token: data.token })
}
