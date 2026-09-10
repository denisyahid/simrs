import { generateToken } from '../../../src/api.js'
import getDaftarRegAmbulance from './get-daftar-reg-ambulance.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getDaftarRegAmbulance({ token: data.token })
}
