import { generateToken } from '../../../src/api.js'
import getRincianAmb from './get-rincian-amb.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getRincianAmb({ token: data.token })
}
