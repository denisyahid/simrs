export function pengkajianSebelumOperasi(): any {
  return [
    {
      "title": "1. Tanda-tanda vital",
      "type": "textBox",
      "detail": [
        {
          "label": "Suhu",
          "model": "suhu",
        },
        {
          "label": "Nadi",
          "model": "nadi",
        },
        {
          "label": "Pernapasan",
          "model": "pernapasan",
        },
        {
          "label": "Tekanan Darah",
          "model": "nadi",
        },
        {
          "label": "Skor Nyeri",
          "model": "skorNyeri",
        },
      ]
    },
    {
      "title": "2. Tingkat Kesadaran",
      "type": "checkBox",
      "detail": [
        {
          "label": "Sadar Penuh",
          "model": "tingkatKesadaran_1",
        },
        {
          "label": "Bingung",
          "model": "tingkatKesadaran_2",
        },
        {
          "label": "Gelisah",
          "model": "tingkatKesadaran_3",
        },
        {
          "label": "Mengantuk",
          "model": "tingkatKesadaran_4",
        },
        {
          "label": "Koma",
          "model": "tingkatKesadaran_5",
        },
      ]
    },
    {
      "title": "3. Riwayat Penyakit",
      "type": "checkBox",
      "detail": [
        {
          "label": "Hipertensi",
          "model": "riwayatPenyakit_1",
        },
        {
          "label": "Diabetes",
          "model": "riwayatPenyakit_2",
        },
        {
          "label": "Hebatitis",
          "model": "riwayatPenyakit_3",
        },
        {
          "label": "Lain-lain",
          "model": "riwayatPenyakit_4",
        },
      ],
      "optional": {
        "model": "ketRiwayatPen"
      }
    },
    {
      "title": "4. Operasi Sebelumnya",
      "type": "textBox",
      "detail": [
        {
          "label": "Jam Operasi",
          "model": "jamOperasi",
        },
        {
          "label": "Kapan",
          "model": "Kapan",
        },
        {
          "label": "Di",
          "model": "tempat",
        },
      ]
    },
    {
      "title": "5. Pengobatan saat ini",
      "type": "checkBox",
      "detail": [
        {
          "label": "Tidak ada",
          "model": "suhu",
        },
        {
          "label": "Tidak diketahui",
          "model": "nadi",
        },
        {
          "label": "ada, Jelaskan",
          "model": "rr",
        },
      ],
      "optional": {
        "model": "ketPengobatan"
      }
    },
    {
      "title": "6. Hasil laboratorium",
      "type": "checkBox",
      "detail": [
        {
          "label": "DL",
          "model": "hasilLab_1",
        },
        {
          "label": "HCV",
          "model": "hasilLab_2",
        },
        {
          "label": "PT/APT",
          "model": "hasilLab_3",
        },
        {
          "label": "Golongan Darah",
          "model": "hasilLab_4",
        },
        {
          "label": "Urine",
          "model": "hasilLab_5",
        },
        {
          "label": "HbsAg",
          "model": "hasilLab_6",
        },
        {
          "label": "HIV",
          "model": "hasilLab_7",
        },
        {
          "label": "Lain-lain",
          "model": "hasilLab_8",
        },
      ],
      "optional": {
        "model": "ketHasilLab"
      }
    },
  ]
}


export function persiapanOperasi(): any {
  return [
    {
      "title": "I. VERIFIKASI PASIEN",
      "details": [
        {
          "subTitle": "1. Pemeriksaan Identifikasi Pasien",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaIden",
                  "label": "Y",
                },
                {
                  "model": "irnaIden",
                  "label": "T",
                },
                {
                  "model": "irnaIden",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarOperasiIden",
                  "label": "Y",
                },
                {
                  "model": "kamarOperasiIden",
                  "label": "T",
                },
                {
                  "model": "kamarOperasiIden",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketIdentifikasi"
          }
        },
        {
          "subTitle": "2. Pemeriksaan gelang identitas",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaGelangIden",
                  "label": "Y",
                },
                {
                  "model": "irnaGelangIden",
                  "label": "T",
                },
                {
                  "model": "irnaGelangIden",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kmrGeleangIden",
                  "label": "Y",
                },
                {
                  "model": "kmrGeleangIden",
                  "label": "T",
                },
                {
                  "model": "kmrGeleangIden",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketGelangIdentitas"
          }

        },
        {
          "subTitle": "3. Surat pengantar rencana operasi",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaPO",
                  "label": "Y",
                },
                {
                  "model": "irnaPO",
                  "label": "T",
                },
                {
                  "model": "irnaPO",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarPO",
                  "label": "Y",
                },
                {
                  "model": "kamarPO",
                  "label": "T",
                },
                {
                  "model": "kamarPO",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketPO"
          }

        },
        {
          "subTitle": "4. Tanda dan lokasi pembedahan (diperiksa bersama pasien)",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaLP",
                  "label": "Y",
                },
                {
                  "model": "irnaLP",
                  "label": "T",
                },
                {
                  "model": "irnaLP",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarLP",
                  "label": "Y",
                },
                {
                  "model": "kamarLP",
                  "label": "T",
                },
                {
                  "model": "kamarLP",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketLP"
          }

        },
        {
          "subTitle": "5. Periksa kelengkapan persetujuan pembedahan",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaPP",
                  "label": "Y",
                },
                {
                  "model": "irnaPP",
                  "label": "T",
                },
                {
                  "model": "irnaPP",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarPP",
                  "label": "Y",
                },
                {
                  "model": "kamarPP",
                  "label": "T",
                },
                {
                  "model": "kamarPP",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketPP"
          }
        },
        {
          "subTitle": "6. Periksa kelengkapan persetujuan anestesi",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaPA",
                  "label": "Y",
                },
                {
                  "model": "irnaPA",
                  "label": "T",
                },
                {
                  "model": "irnaPA",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarPA",
                  "label": "Y",
                },
                {
                  "model": "kamarPA",
                  "label": "T",
                },
                {
                  "model": "kamarPA",
                  "label": "TM",
                },
              ]
            },
          ],

          "optional": {
            "label": "Keterangan",
            "model": "ketPA"
          }

        },
        {
          "subTitle": "7. Periksa kelengkapan dokumen medis (rawat inap dan rawatjalan)",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaDM",
                  "label": "Y",
                },
                {
                  "model": "irnaDM",
                  "label": "T",
                },
                {
                  "model": "irnaDM",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarDM",
                  "label": "Y",
                },
                {
                  "model": "kamarDM",
                  "label": "T",
                },
                {
                  "model": "kamarDM",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketDM"
          }

        },
        {
          "subTitle": "8. Periksa kelengkapan penunjang (laboratorium, radiologi)",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaKP",
                  "label": "Y",
                },
                {
                  "model": "irnaKP",
                  "label": "T",
                },
                {
                  "model": "irnaKP",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarKP",
                  "label": "Y",
                },
                {
                  "model": "kamarKP",
                  "label": "T",
                },
                {
                  "model": "kamarKP",
                  "label": "TM",
                },
              ]
            },
          ],

          "optional": {
            "label": "Keterangan",
            "model": "ketKP"
          }

        },
      ]
    },
  ]
}

export function persiapanOperasiII(): any {
  return [
    {
      "title": "II. PERSIAPAN FISIK PASIEN",
      "details": [
        {
          "subTitle": "1. Puasa/ makan dan minum terakhir,jam",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaMakananTerak",
                  "label": "Y",
                },
                {
                  "model": "irnaMakananTerak",
                  "label": "T",
                },
                {
                  "model": "irnaMakananTerak",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarMakananTerak",
                  "label": "Y",
                },
                {
                  "model": "kamarMakananTerak",
                  "label": "T",
                },
                {
                  "model": "kamarMakananTerak",
                  "label": "TM",
                },
              ]
            },
          ],

          "optional": {
            "label": "Keterangan",
            "model": "ketMakananTerakhir"
          }

        },
        {
          "subTitle": "2. IV line no .... cairan",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaCairan",
                  "label": "Y",
                },
                {
                  "model": "irnaCairan",
                  "label": "T",
                },
                {
                  "model": "irnaCairan",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarMakananTerak",
                  "label": "Y",
                },
                {
                  "model": "kamarMakananTerak",
                  "label": "T",
                },
                {
                  "model": "kamarMakananTerak",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketMakananTerakhir"
          }
        },
        {
          "subTitle": "3. Protese luar (gigi palsu, lensa kontak)",
          "detail": [
            {
              "judul": "IRNA",
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarOperasiPL",
                  "label": "Y",
                },
                {
                  "model": "kamarOperasiPL",
                  "label": "T",
                },
                {
                  "model": "kamarOperasiPL",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketProtesLuar"
          }
        },
        {
          "subTitle": "4. Menggunakan protese dalam (implant, psotese panggul/bahu dll)",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaProDA",
                  "label": "Y",
                },
                {
                  "model": "irnaProDA",
                  "label": "T",
                },
                {
                  "model": "irnaProDA",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarOperasiProDa",
                  "label": "Y",
                },
                {
                  "model": "kamarOperasiProDa",
                  "label": "T",
                },
                {
                  "model": "kamarOperasiProDa",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketProteseDalam"
          }
        },
        {
          "subTitle": "5. Penjepit rambut/cat kuku/perhiasan (dilepaskan)",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaPenRam",
                  "label": "Y",
                },
                {
                  "model": "irnaPenRam",
                  "label": "T",
                },
                {
                  "model": "irnaPenRam",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarOperasiPenRam",
                  "label": "Y",
                },
                {
                  "model": "kamarOperasiPenRam",
                  "label": "T",
                },
                {
                  "model": "kamarOperasiPenRam",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketPenjepitRambut"
          }
        },
        {
          "subTitle": "6. Persiapan kulit /cukur",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaPerKulit",
                  "label": "Y",
                },
                {
                  "model": "irnaPerKulit",
                  "label": "T",
                },
                {
                  "model": "irnaPerKulit",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarOperasiPerKulit",
                  "label": "Y",
                },
                {
                  "model": "kamarOperasiPerKulit",
                  "label": "T",
                },
                {
                  "model": "kamarOperasiPerKulit",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketPerKulit"
          }
        },
        {
          "subTitle": "7. Pengosongan kandung kemih",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaKandKemih",
                  "label": "Y",
                },
                {
                  "model": "irnaKandKemih",
                  "label": "T",
                },
                {
                  "model": "irnaKandKemih",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarOperasiKandKemih",
                  "label": "Y",
                },
                {
                  "model": "kamarOperasiKandKemih",
                  "label": "T",
                },
                {
                  "model": "kamarOperasiKandKemih",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketKandKemih"
          }
        },
        {
          "subTitle": "8. Memerlukan darah",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaMemerlukanDarah",
                  "label": "Y",
                },
                {
                  "model": "irnaMemerlukanDarah",
                  "label": "T",
                },
                {
                  "model": "irnaMemerlukanDarah",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarOperasiMemDarah",
                  "label": "Y",
                },
                {
                  "model": "kamarOperasiMemDarah",
                  "label": "T",
                },
                {
                  "model": "kamarOperasiMemDarah",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketMemerlukanDarah"
          }
        },
        {
          "subTitle": "9. Alat bantu (kacamata, alat bantu dengar) disimpan",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaAlatBantu",
                  "label": "Y",
                },
                {
                  "model": "irnaAlatBantu",
                  "label": "T",
                },
                {
                  "model": "irnaAlatBantu",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarOperasiAlatBantu",
                  "label": "Y",
                },
                {
                  "model": "kamarOperasiAlatBantu",
                  "label": "T",
                },
                {
                  "model": "kamarOperasiAlatBantu",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketAlatBantu"
          }
        },
        {
          "subTitle": "10. Riwayat Obat",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaRiwayatObat",
                  "label": "Y",
                },
                {
                  "model": "irnaRiwayatObat",
                  "label": "T",
                },
                {
                  "model": "irnaRiwayatObat",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarOperasiRiwObat",
                  "label": "Y",
                },
                {
                  "model": "kamarOperasiRiwObat",
                  "label": "T",
                },
                {
                  "model": "kamarOperasiRiwObat",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketRiwayatObat"
          }
        },
        {
          "subTitle": "11. Obat antibiotika terakhir yang diberikan",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaOAT",
                  "label": "Y",
                },
                {
                  "model": "irnaOAT",
                  "label": "T",
                },
                {
                  "model": "irnaOAT",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarOAT",
                  "label": "Y",
                },
                {
                  "model": "kamarOAT",
                  "label": "T",
                },
                {
                  "model": "kamarOAT",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketObatAntTerakhir"
          }
        },
        {
          "subTitle": "12. Vaskular akses (cimino) dan lain-lain",
          "detail": [
            {
              "judul": "IRNA",
              "value": [
                {
                  "model": "irnaCimino",
                  "label": "Y",
                },
                {
                  "model": "irnaCimino",
                  "label": "T",
                },
                {
                  "model": "irnaCimino",
                  "label": "TM",
                },
              ]
            },
            {
              "judul": "Kamar Operasi",
              "value": [
                {
                  "model": "kamarOperasiCimino",
                  "label": "Y",
                },
                {
                  "model": "kamarOperasiCimino",
                  "label": "T",
                },
                {
                  "model": "kamarOperasiCimino",
                  "label": "TM",
                },
              ]
            },
          ],
          "optional": {
            "label": "Keterangan",
            "model": "ketCimino"
          }
        },
      ]
    },
  ]
}

export function pengkajianIntra():any{
  return [
    {
      "title" : "1. Cek Ketersediaan peralatan dan fungsinya"
    },
    {
      "title" : "2. Operasi yang dilakukan",
      "detail" : [
        {
          "subTitle" : "a. Instrumen",
          "value" : [
            {
              "label" : "Ya",
              "model" : "instrumen",
            },
            {
              "label" : "Tidak",
              "model" : "instrumen",
            }
          ]
        },
        {
          "subTitle" : "b. Protese/implan",
          "value" : [
            {
              "label" : "Ya",
              "model" : "implan",
            },
            {
              "label" : "Tidak",
              "model" : "implan",
            }
          ]
        }
      ]
    },
    {
      "title" : "3. Timeout",
      "detail" : [
        {
          "value" : [
            {
              "label" : "Ya",
              "model" : "timeout",
            },
            {
              "label" : "Tidak",
              "model" : "timeout",
            }
          ]
        },
      ]
    },
    {
      "title" : "4. Tipe operasi",
      "detail" : [
        {
          "value" : [
            {
              "label" : "Elektif",
              "model" : "tipeOperasi",
            },
            {
              "label" : "Darurat",
              "model" : "tipeOperasi",
            },
            {
              "label" : "ODC",
              "model" : "tipeOperasi",
            },
          ]
        },
      ]
    },
    {
      "title" : "5. Status emosi waktu masuk kamar operasi",
      "detail" : [
        {
          "value" : [
            {
              "label" : "Rileks",
              "model" : "statusEmosi",
            },
            {
              "label" : "Gelisah",
              "model" : "statusEmosi",
            },
            {
              "label" : "Tidak ada Respon",
              "model" : "statusEmosi",
            },
          ]
        },
      ]
    },
    {
      "title" : "6. Posisi kanul lntra vena",
      "detail" : [
        {
          "value" : [
            {
              "label" : "Ta-kiri/Kanan",
              "model" : "posisiKanul",
            },
            {
              "label" : "Ka-kiri/Kanan",
              "model" : "posisiKanul",
            },
            {
              "label" : "Artelia Line",
              "model" : "posisiKanul",
            },
            {
              "label" : "CVP",
              "model" : "posisiKanul",
            },
            {
              "label" : "Lain-lain",
              "model" : "posisiKanul",
            },
          ],
          "optional" : {
            "model" : "ketPosisiKanul"
          }
        },
      ],
    },
    {
      "title" : "7. Posisi Operasi",
      "detail" : [
        {
          "value" : [
            {
              "label" : "Terlantang",
              "model" : "posisiOperasi",
            },
            {
              "label" : "Lithothomy",
              "model" : "posisiOperasi",
            },
            {
              "label" : "Tengkurap",
              "model" : "posisiOperasi",
            },
            {
              "label" : "Lateral Kiri-Kanan",
              "model" : "posisiOperasi",
            },
            {
              "label" : "Lain-lain",
              "model" : "posisiOperasi",
            },
          ],
          "optional" : {
            "model" : "ketPosisiOperasi"
          }
        },
      ],
    },
    {
      "title" : "8. Pemakaian dianthermy",
      "detail" : [
        {
          "value" : [
            {
              "label" : "Tidak",
              "model" : "pemDianthermy",
            },
            {
              "label" : "Bipolar",
              "model" : "pemDianthermy",
            },
            {
              "label" : "Monopolar",
              "model" : "pemDianthermy",
            },
          ]
        },
        {
          "subTitle" : "- Lokasi dari dipensive elektroda",
          "value" : [
            {
              "label" : "Bokong Kanan/Kiri",
              "model" : "lokasiDispensive",
            },
            {
              "label" : "Paha Kanan/Kiri",
              "model" : "lokasiDispensive",
            },
            {
              "label" : "Betis Kanan/Kiri",
              "model" : "lokasiDispensive",
            },
          ]
        },
        {
          "subTitle" : "- Pemeriksaan kondisi kulit sebelum operasi",
          "value" : [
            {
              "label" : "Utuh",
              "model" : "kondisiKulitSeb",
            },
            {
              "label" : "Mengelembung",
              "model" : "kondisiKulitSeb",
            },
            {
              "label" : "Lain-lain",
              "model" : "kondisiKulitSeb",
            },
          ],
          "optional" : {
            "model" : "ketKondisiKulitSeb"
          }
        },
        {
          "subTitle" : "- Pemeriksaan kondisi kulit sesudah operasi",
          "value" : [
            {
              "label" : "Utuh",
              "model" : "kondisiKulitSes",
            },
            {
              "label" : "Mengelembung",
              "model" : "kondisiKulitSes",
            },
            {
              "label" : "Lain-lain",
              "model" : "kondisiKulitSes",
            },
          ],
          "optional" : {
            "model" : "ketKondisiKulitSes"
          }
        },
      ],
    },
  ]
}

export function pemakian():any{
  return [
    {
      "title" : "12. Pemakaian Implan",
      "value" : [
        {
          "label" : "Ya",
          "model" : "pmkImplan",
        },
        {
          "label" : "Tidak",
          "model" : "pmkImplan",
        },
      ]
    },
    {
      "title" : "13. Pemakaian drain, vacum/tidak vacum",
      "value" : [
        {
          "label" : "Ya",
          "model" : "pmkDrain",
        },
        {
          "label" : "Tidak",
          "model" : "pmkDrain",
        },
      ]
    },
    {
      "title" : "13. Penutup Luka",
      "value" : [
        {
          "label" : "Ya",
          "model" : "penutupLuka",
        },
        {
          "label" : "Tidak",
          "model" : "penutupLuka",
        },
      ]
    },
    {
      "title" : "15. Spesimen",
      "value" : [
        {
          "label" : "Tidak ada",
          "model" : "spesimen",
        },
        {
          "label" : "Ada",
          "model" : "spesimen",
        },
      ],
      "optional" : {
        "label" : "Nama dari jaringan",
        "model" : "namaJaringan"
      }
    },
  ]
}

export function pengkajianSesudahOP():any{
  return [
    {
      "title" : "1. Keadaan umum",
      "value" : [
        {
          "label" : "Baik",
          "value" : "Baik",
          "model" : "keadaanUmum",
          "type" : "checkBox",
        },
        {
          "label" : "Jelak, Jelaskan",
          "value" : "Jelek",
          "model" : "keadaanUmum",
          "type" : "checkBox",
        },
        {
         "model" : "ketKeadaanUmum",
         "type" : "textBox",
        },
      ]
    },
    {
      "title" : "2. Tingkat Kesadaran",
      "value" : [
        {
          "label" : "Sadar",
          "model" : "kesadaran",
          "value" : "sadar",
          "type" : "checkBox",
        },
        {
          "label" : "Mudah dibangunkan",
          "model" : "kesadaran",
          "value" : "MN",
          "type" : "checkBox",
        },
        {
          "label" : "Tidak ada respon",
          "model" : "kesadaran",
          "value" : "TAR",
          "type" : "checkBox",
        }
      ]
    },
    {
      "title" : "3. Jalan Nafas Datang",
      "value" : [
        {
          "label" : "Patent",
          "model" : "jlNafasDatang",
          "value" : "PT",
          "type" : "checkBox",
        },
        {
          "label" : "Tidak Patent",
          "model" : "jlNafasDatang",
          "value" : "TP",
          "type" : "checkBox",
        },
        {
          "label" : "Lain-lain",
          "model" : "jlNafasDatang",
          "value" : "LN",
          "type" : "checkBox",
        },
        {
          "model" : "ketJalanNafasDT",
          "type" : "textBox",
        }
      ]
    },
    {
      "title" : "Jalan Nafas Keluar",
      "value" : [
        {
          "label" : "Patent",
          "model" : "jlNafasKeluar",
          "value" : "PT",
          "type" : "checkBox",
        },
        {
          "label" : "Tidak Patent",
          "model" : "jlNafasKeluar",
          "value" : "TP",
          "type" : "checkBox",
        },
        {
          "label" : "Lain-lain",
          "model" : "jlNafasKeluar",
          "value" : "LN",
          "type" : "checkBox",
        },
        {
          "model" : "ketJalanNafasKL",
          "type" : "textBox",
        }
      ]
    },
    {
      "title" : "4. Terapi oksigen",
      "value" : [
        {
          "label" : "l/mnt",
          "model" : "terapiOksigen",
          "type" : "textBox",
        },
        {
          "label" : "Jenis",
          "model" : "jenisTerapi",
          "type" : "textBox",
        },
      ]
    },
    {
      "title" : "5. Posisi pasien",
      "value" : [
        {
          "label" : "Lateral kanan-kiri",
          "model" : "posisiPasien",
          "value" : "LKN",
          "type" : "checkBox",
        },
        {
          "label" : "Semi fowler",
          "model" : "posisiPasien",
          "value" : "SF",
          "type" : "checkBox",
        },
        {
          "label" : "Lain-lain",
          "model" : "posisiPasien",
          "value" : "LN",
          "type" : "checkBox",
        },
        {
          "model" : "ketPosisiPasien",
          "type" : "textBox",
        }
      ]
    },
    {
      "title" : "6. Keterangan dokumen yang diserahkan",
      "value" : [
        {
          "label" : "Laporan anestesi",
          "model" : "dokDiserahkan",
          "value" : "LA",
          "type" : "checkBox",
        },
        {
          "label" : "Laporan operasi",
          "model" : "dokDiserahkan",
          "value" : "LO",
          "type" : "checkBox",
        },
        {
          "label" : "Lain-lain",
          "model" : "dokDiserahkan",
          "value" : "LN",
          "type" : "checkBox",
        },
        {
          "model" : "ketDokumenDiserahkan",
          "type" : "textBox",
        }
      ]
    },
  ]
}

export function kulit():any{
  return [
    {
      "title" : "7. Kulit",
      "detail" : [
        {
          "subTitle" : "Datang",
          "value" : [
            {
              "label" : "Patent",
              "code" : "PT",
              "model" : "kulitDatang",
              "type" : "checkBox",
            },
            {
              "label" : "Tidak Patent",
              "code" : "TP",
              "model" : "kulitDatang",
              "type" : "checkBox",
            },
            {
              "label" : "Lain-lain",
              "code" : "LN",
              "model" : "kulitDatang",
              "type" : "checkBox",
            },
            {
              "model" : "ketKulitDatang",
              "type" : "textBox",
            }
          ],
        },
        {
          "subTitle" : "Keluar",
          "value" : [
            {
              "label" : "Patent",
              "code" : "PT",
              "model" : "kulitKeluar",
              "type" : "checkBox",
            },
            {
              "label" : "Tidak Patent",
              "code" : "TP",
              "model" : "kulitKeluar",
              "type" : "checkBox",
            },
            {
              "label" : "Lain-lain",
              "code" : "LN",
              "model" : "kulitKeluar",
              "type" : "checkBox",
            },
            {
              "model" : "ketKulitKeluar",
              "type" : "textBox",
            }
          ],
        }
      ]
    }
  ]
}
