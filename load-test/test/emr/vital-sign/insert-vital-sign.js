import { check } from 'k6'
import { generateToken, getDaftarAntrianRawatJalan, saveEMRDinamis } from '../../../src/api.js'

export function setup () {
  return {
    token:generateToken().json()["response"]["token"]
  }
}

export default async function (data) {
  const from = new Date()
  const to = new Date()
  to.setDate(to.getDate() - 1)
  let listPasien = await  getDaftarAntrianRawatJalan({tglAwal:from, tglAkhir:to,jmlRow:1 }, { token:data.token })
  listPasien = listPasien.status === 200  ? listPasien.json(): []
  const res = saveEMRDinamis(
    {
      head: {
        nocm: listPasien.length > 0 ? listPasien[0].nocm : '000001',
        namapasien:  listPasien.length > 0 ? listPasien[0].namapasien.substr(0,49) : 'TN. EGIE RAMDAN',
        jeniskelamin: listPasien.length > 0 ? listPasien[0].jeniskelamin : 'LAKI-LAKI',
        noregistrasi: listPasien.length > 0 ? listPasien[0].noregistrasi : '2210000002',
        umur: '25thn 7bln 20hr ',
        kelompokpasien:  listPasien.length > 0 ? listPasien[0].kelompokpasien :'Umum/Pribadi',
        tglregistrasi:  listPasien.length > 0 ? listPasien[0].tglregistrasi :'2022-10-19 14:28:50',
        norec: listPasien.length > 0 ? listPasien[0].norec_apd :  '04c6dd30-4f81-11ed-801c-8f699436',
        norec_pd: listPasien.length > 0 ? listPasien[0].norec_pd :'04c11330-4f81-11ed-b52b-5137a562',
        objectkelasfk: 6,
        namakelas: listPasien.length > 0 ? listPasien[0].namakelas : 'Non Kelas',
        objectruanganfk:  listPasien.length > 0 ? listPasien[0].objectruanganfk :663,
        namaruangan: listPasien.length > 0 ? listPasien[0].namaruangan : 'POLI UMUM',
        DataNoregis: true,
        norec_emr: '-',
        emrfk: '147',
        jenisemr: 'navigasi'
      },
      data: [
        {
          id: '3105603',
          values: 22
        },
        {
          id: '4246',
          values: 30
        },
        {
          id: '4245',
          values: 100
        },
        {
          id: '4244',
          values: 32
        },
        {
          id: '4243',
          values: 65
        },
        {
          id: '4242',
          values: 170
        },
        {
          id: '4241',
          values: '120/80'
        }
      ]
    },
    { token: data.token }
  )
  check(res, {
    'insert-vital-sign is status 201': r => r.status === 201
  })
}
