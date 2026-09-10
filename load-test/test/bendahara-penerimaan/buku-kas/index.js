import { generateToken } from '../../../src/api.js'
import getBukuKas from './get-buku-kas.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getBukuKas({ token: data.token })
}
