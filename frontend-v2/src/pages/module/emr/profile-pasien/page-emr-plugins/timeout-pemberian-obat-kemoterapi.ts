export function pernyataanKriteria(): any {
  return [
    {
      label: 'Form edukasi untuk pasien/keluarganya sudah lengkap',
      model: 'formEdukasi',
      detailObat: [
        {
          labelObat: 'Obat1',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat3',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat4',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat5',
          checkBoxObat: [

          ],
        }
      ],
    },
    {
      label: 'Persetujuan tindakan kemoterapi sudah ditandatangani oleh pasien/keluarga dan DPJP',
      model: 'persetujuan',
      detailObat: [
        {
          labelObat: 'Obat1persetujuan',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2persetujuan',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat3persetujuan',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat4persetujuan',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat5persetujuan',
          checkBoxObat: [

          ],
        }
      ],
    },
    {
      label: 'Medication order : terkini, dapat dibaca dengan jelas dan sudah ditandatangani oleh DPJP',
      model: 'medicationOrder',
      detailObat: [
        {
          labelObat: 'Obat1medicationOrder',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2medicationOrder',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat3medicationOrder',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat4medicationOrder',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat5medicationOrder',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        }
      ],
    },
    {
      label: 'Pengkajian awal kemoterapi sudah terisi dengan lengkap',
      model: 'pengkajianAwal',
      detailObat: [
        {
          labelObat: 'Obat1pengkajianAwal',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2pengkajianAwal',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat3pengkajianAwal',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat4pengkajianAwal',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat5pengkajianAwal',
          checkBoxObat: [

          ],
        }
      ],
    },
    {
      label: 'Hasil laboratorium tersedia dan sudah dikonfirmasi oleh DPJP',
      model: 'Laboratorium',
      detailObat: [
        {
          labelObat: 'Obat1Laboratorium',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2Laboratorium',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat3Laboratorium',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat4Laboratorium',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat5Laboratorium',
          checkBoxObat: [

          ],
        }
      ],
    },
    {
      label: 'Nama pasien dan nomor RM yang tertera pada obat kemoterapi sesuai dengan gelang identitas yang dipakai oleh pasien',
      model: 'NamaPasien',
      detailObat: [
        {
          labelObat: 'Obat1NamaPasien',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2NamaPasien',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat3NamaPasien',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat4NamaPasien',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat5NamaPasien',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        }
      ],
    },
    {
      label: 'Protokol kemoterapi, seri dan harinya sudah sesuai',
      model: 'Protokol',
      detailObat: [
        {
          labelObat: 'Obat1Protokol',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2Protokol',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat3Protokol',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat4Protokol',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat5Protokol',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        }
      ],
    },
    {
      label: 'Hitung BSA pasien. Dosis obat sudah sesuai dengan BSA pasien (j ika ≥ 10% maka lakukan penghitungan ulang',
      model: 'HitungBSA',
      detailObat: [
        {
          labelObat: 'Obat1HitungBSA',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2HitungBSA',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat3HitungBSA',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat4HitungBSA',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat5HitungBSA',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        }
      ],
    },
    {
      label: 'Tanggal dan jam kadaluarsa obat kemoterapi sudah dicek',
      model: 'JamKadaluarsa',
      detailObat: [
        {
          labelObat: 'Obat1JamKadaluarsa',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2JamKadaluarsa',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat3JamKadaluarsa',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat4JamKadaluarsa',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat5JamKadaluarsa',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        }
      ],
    },
    {
      label: 'Akses vena pemasangan infus lancar',
      model: 'AksesVena',
      detailObat: [
        {
          labelObat: 'Obat1AksesVena',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2AksesVena',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat3AksesVena',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat4AksesVena',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat5AksesVena',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        }
      ],
    },
    {
      label: 'Line infus yang tersedia sudah sesuai dengan banyaknya regimen yang akan diberikan ditambah satu line untuk flushing',
      model: 'LineInfus',
      detailObat: [
        {
          labelObat: 'Obat1LineInfus',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2LineInfus',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat3LineInfus',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat4LineInfus',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat5LineInfus',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        }
      ],
    },
    {
      label: 'Kecepatan tetesan infus sudah sesuai dengan banyaknya cairan yang akan diberikan',
      model: 'KecepartanInfus',
      detailObat: [
        {
          labelObat: 'Obat1KecepartanInfus',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2KecepartanInfus',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat3KecepartanInfus',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat4KecepartanInfus',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat5KecepartanInfus',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        }
      ],
    },
    {
      label: 'Perjanjian kontrol pasien selanjutnya sudah dijelaskan kepada pasien/keluarga',
      model: 'PerjanjianKontrol',
      detailObat: [
        {
          labelObat: 'Obat1PerjanjianKontrol',
          checkBoxObat: [
            {
              label: 'ya',
              value: 'ya',
            },
            {
              label: 'tidak',
              value: 'tidak',
            },
          ],
        },
        {
          labelObat: 'Obat2PerjanjianKontrol',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat3PerjanjianKontrol',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat4PerjanjianKontrol',
          checkBoxObat: [

          ],
        },
        {
          labelObat: 'Obat5PerjanjianKontrol',
          checkBoxObat: [

          ],
        }
      ],
    },
  ]
}
