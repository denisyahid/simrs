import { generateToken } from '../../src/api.js'
import ambilAntrian from './ambil-antrian.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  ambilAntrian({ token: data.token })
}
