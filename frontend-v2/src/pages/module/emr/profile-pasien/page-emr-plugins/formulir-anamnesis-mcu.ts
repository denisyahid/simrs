export function formulirGeneral(): any {
    return [
        {
            "title": "General Medical Check Up",
            "detail": [
                {
                    "label": "Untuk Keperluan :",
                    "model": "keperluan",
                    "type": "textBox"
                },
                {
                    "label": "Syarat masuk Institusi",
                    "model": "institusi",
                    "type": "textBox"
                },

            ]
        },
        {
            "title": "Identias",
            "detail": [
                {
                    "label": "Nama",
                    "model": "nama",
                    "type": "textBox"
                },
                {
                    "label": "Telepone",
                    "model": "telpone",
                    "type": "textBox"
                },
                {
                    "label": "Alamat",
                    "model": "alamat",
                    "type": "textBox"
                },
                {
                    "label": "Tempat Lahir",
                    "model": "temtLahir",
                    "type": "textBox"
                },
                {
                    "label": "Tanggal Lahir",
                    "model": "tglLahir",
                    "type": "date"
                },
                {
                    "label": "Kewarganegaraan",
                    "model": "kewarganegaraan",
                    "type": "textBox"
                },
            ]
        },
    ]
}

export function riwayatHubungan(): any {
    return ['Menikah', 'Single']
}

export function riwayatKeluarga(): any {
    return [
        {
            "hubungan": "Ayah",
            "usia": "usiaAyah",
            "kondisi": "kondisiAyah",
            "usiaKem": "usiaKemAyah"
        },
        {
            "hubungan": "Ibu",
            "usia": "usiaIbu",
            "kondisi": "kondisiIbu",
            "usiaKem": "usiaKemIbu"
        },
        {
            "hubungan": "Adik",
            "usia": "usiaAdik",
            "kondisi": "kondisiAdik",
            "usiaKem": "usiaKemAdik"
        },
        {
            "hubungan": "Kakak",
            "usia": "usiaKakak",
            "kondisi": "kondisiKakak",
            "usiaKem": "usiaKemKakak"
        },
        {
            "hubungan": "Pasangan",
            "usia": "usiaPasangan",
            "kondisi": "kondisiPasangan",
            "usiaKem": "usiaKemPasangan"
        },
        {
            "hubungan": "Anak",
            "usia": "usiaAnak",
            "kondisi": "kondisiAnak",
            "usiaKem": "usiaKemAnak"
        },
        {
            "hubungan": "Lainnya",
            "usia": "usiaLainnya",
            "kondisi": "kondisiLainnya",
            "usiaKem": "usiaKemLainnya"
        },
    ]
}

export function listPeyakit(): any {
    return [
        {
            "jenisSakit": "Tekanan darah tinggi",
            "model": "berpenyakitDarahTinggi",
            "pilihan_1": "Ya",
            "pilihan_2": "Tidak",
            "siapa": "bersangkutan"
        },
        {
            "jenisSakit": "Penyakit jantung",
            "model": "berpenyakitJantung",
            "pilihan_1": "Ya",
            "pilihan_2": "Tidak",
            "siapa": "bersangkutan"
        },
        {
            "jenisSakit": "Diabetes",
            "model": "berpenyakitDiabetes",
            "pilihan_1": "Ya",
            "pilihan_2": "Tidak",
            "siapa": "bersangkutan"
        },
        {
            "jenisSakit": "Tuberkulosis",
            "model": "berpenyakitTuberkulosis",
            "pilihan_1": "Ya",
            "pilihan_2": "Tidak",
            "siapa": "bersangkutan"
        },
        {
            "jenisSakit": "Asthma",
            "model": "berpenyakitAsthma",
            "pilihan_1": "Ya",
            "pilihan_2": "Tidak",
            "siapa": "bersangkutan"
        },
        {
            "jenisSakit": "Kanker",
            "model": "berpenyakitKanker",
            "pilihan_1": "Ya",
            "pilihan_2": "Tidak",
            "siapa": "bersangkutan"
        },
        {
            "jenisSakit": "Epilepsi",
            "model": "berpenyakitEpilepsi",
            "pilihan_1": "Ya",
            "pilihan_2": "Tidak",
            "siapa": "bersangkutan"
        },
        {
            "jenisSakit": "Gangguan mental",
            "model": "berpenyakitMental",
            "pilihan_1": "Ya",
            "pilihan_2": "Tidak",
            "siapa": "bersangkutan"
        },
        {
            "jenisSakit": "Paralysis",
            "model": "berpenyakitParalysis",
            "pilihan_1": "Ya",
            "pilihan_2": "Tidak",
            "siapa": "bersangkutan"
        },
    ]
}

export function riwayatPenyakit(): any {
    return [
        {
            "penyakit": {
                "L": "Nyeri tenggorokan berulang",
                "R": "Hemorrhoid"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Hidung alergi",
                "R": "Gangguan perkemihan"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Asthma",
                "R": "Penyakit ginjal"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Tuberkulosis",
                "R": "Batu ginjal"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Pneumonia",
                "R": "Nyeri pinggang"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Radang selaput paru",
                "R": "Masalah sendi"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Bronchitis berulang",
                "R": "Penyakit kulit"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Demam rematik",
                "R": "Sulit tidur"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Tekanan darah tinggi",
                "R": "Penyakit saraf atau penyakit mental"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Penyakit jantung dan pembuluh",
                "R": "Nyeri kepala berulang"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Nyeri di daerah dada",
                "R": "Episode pingsan"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Varises",
                "R": "Disentri amoeba"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Tidak enak perut berulang (indigestion)",
                "R": "Malaria"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Tukak lambung atau duodenum",
                "R": "Epilepsi"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Penyakit kuning",
                "R": "Diabeters"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Batu empedu",
                "R": "Gonorrhea"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },
        {
            "penyakit": {
                "L": "Hernia",
                "R": "Penyakit menular seksual"
            },
            "tahun": {
                "L": "lTahun_",
                "R": "rTahun_"
            },
            "check": {
                "L": "lTidak_",
                "R": "rTidak_"
            }
        },

    ]
}

export function pertanyaanPenyakit(): any {
    return [
        {
            "title": "2. Apakah Anda sedang dalam pengobatan ?",
            "value": [
                {
                    "label": "Tidak",
                    "model": "prosesPengobatan",
                },
                {
                    "label": "Ya",
                    "model": "prosesPengobatan",
                },
            ],
            "optional": [
                {
                    "model": "ketPengobatan",
                    'label': "Jelaskan"
                }
            ]
        },
        {
            "title": "3. Apakah Anda pernah batuk darah ?",
            "value": [
                {
                    "label": "Tidak",
                    "model": "batukBerdarah",
                },
                {
                    "label": "Ya",
                    "model": "batukBerdarah",
                },
            ],
            // "optional" : {}
        },
        {
            "title": "4. Apakah anda pernah melihat darah dalam feses anda ?",
            "value": [
                {
                    "label": "Tidak",
                    "model": "melihatDarah",
                },
                {
                    "label": "Ya",
                    "model": "melihatDarah",
                },
                {
                    "label": "Dalam kencing",
                    "model": "melihatDarah",
                },

            ],
            "optional": [
                {
                    "model": "ketDarah",
                    "label": "Tulis Detail"
                }
            ]
        },
        {
            "title": "5. Apakah anda pernah dirawat inap (RS, Klinik, dll) ? ",
            "value": [
                {
                    "label": "Tidak",
                    "model": "pernahOpname",
                },
                {
                    "label": "Ya",
                    "model": "pernahOpname",
                },

            ],
            "optional": [
                {
                    "model": "ketPeranahOpname",
                    "label": "kenapa, dimana, dan kapan?"
                }
            ]
        },
        {
            "title": "6. Apakah anda pernah tidak masuk kerja atau beristirahat kerena sakit lebih dari satu bulan?",
            "value": [
                {
                    "label": "Tidak",
                    "model": "pernahOpname",
                },
                {
                    "label": "Ya",
                    "model": "pernahOpname",
                },
            ],
            "optional": [
                {
                    "label": "Jika Ya, Kapan ?",
                    "model": "pernahOpname",
                },
                {
                    "label": "dan karena sakit apa ?",
                    "model": "pernahOpname",
                },
            ]

        },
        {
            "title": "7. Apakah anda pernah mengalami kecelakaan sehingga menyandang disabilitas ?",
            "value": [
                {
                    "label": "Tidak",
                    "model": "konsulDokter",
                },
                {
                    "label": "Ya",
                    "model": "konsulDokter",
                },
            ],
            "optional": [
                {
                    "label": "jika ya apa dan kapan ?",
                    "model": "ketKonsulDokter",
                },
                {
                    "label": "apakah anda memilik disabilitas lain?",
                    "model": "ketKonsulDokter",
                },
            ]
        },
        {
            "title": "8. Apakah anda pernah berkonsultasi dengan dokter sarf, dokter jiwa atau psikolog? ",
            "value": [
                {
                    "label": "Tidak",
                    "model": "konsulDokter",
                },
                {
                    "label": "Ya",
                    "model": "konsulDokter",
                },

            ],
            "optional": [
                {
                    "label": "jika ya karena apa?",
                    "model": "ketKonsulDokter",
                },
            ]
        },
        {
            "title": "9. Apakah anda mengonsumsi obat-obatan secara rutin ?",
            "value": [
                {
                    "label": "Tidak",
                    "model": "konsulDokter",
                },
                {
                    "label": "Ya",
                    "model": "konsulDokter",
                },

            ],
            "optional": [
                {
                    "label": "jika ya obat apa ?",
                    "model": "ketKonsulDokter",
                }
            ]
        },
        {
            "title": "10. Apakah anda mengalami peningkatan atau penurunan berat badan dalam 3 tahun terakhir ?",
            "value": [
                {
                    "label": "Tidak",
                    "model": "konsulDokter",
                },
                {
                    "label": "Ya",
                    "model": "konsulDokter",
                },
            ],
            "optional": [
                {
                    "label": "jika ya, berapa banyak?",
                    "model": "ketKonsulDokter",
                }
            ]
        },
        {
            "title": "11. Apakah anda pernah ditolak asuransi Kesehatan ?",
            "value": [
                {
                    "label": "Tidak",
                    "model": "konsulDokter",
                },
                {
                    "label": "Ya",
                    "model": "konsulDokter",
                },
            ],
            "optional": [
                {
                    "label": "jika ya, karena apa?",
                    "model": "ketKonsulDokter",
                }
            ]
        },
        {
            "title": "12. Apakah anda ada bepergian dalam 1 tahun terakhir ?",
            "value": [
                {
                    "label": "Tidak",
                    "model": "konsulDokter",
                },
                {
                    "label": "Ya",
                    "model": "konsulDokter",
                },
            ],
            "optional": [
                {
                    "label": "Kapan dan kemana?",
                    "model": "ketKonsulDokter",
                }
            ]
        },
        {
            "title": "13. Apakah anda pernah memiliki kondisi yang menyebabkan tidak bisa perjalanan udara ?",
            "value": [
                {
                    "label": "Tidak",
                    "model": "konsulDokter",
                },
                {
                    "label": "Ya",
                    "model": "konsulDokter",
                },
            ],
            "optional": [
                {
                    "label": "jika ya, kondisi apa?",
                    "model": "ketKonsulDokter",
                }
            ]
        },
        {
            "title": "14. Apakah anda mengkategorikan diri anda sehat ?",
            "value": [
                {
                    "label": "Tidak",
                    "model": "konsulDokter",
                },
                {
                    "label": "Ya",
                    "model": "konsulDokter",
                },
            ],
            "optional": [
                {
                    "label": "apakah anda dapat bekerja dengan kapasitas penuh ?",
                    "model": "ketKonsulDokter",
                }
            ]
        },

    ]
}

export function pertanyaanMerokok(): any {
    return [
        {
            "title": "15. Apakah anda merokok?",
            "detail": [
                {
                    "subTitle": "",
                    "model": "merokok",
                    "value": ["Tidak", "Ya"]
                },
                {
                    "subTitle": "Jika ya merokok apa ?",
                    "model" : "modelRokok",
                    "value" : ["Kretek","Cerutu"]
                }
            ],
            "optional" : [
                {
                    "Label" : "Berapa tahun anda merokok ?",
                    "model" : "durasiMerokok",
                },
                {
                    "Label" : "Berapa banyak perhari ?",
                    "model" : "jmlPerhari",
                }
            ]
        }
    ]
}

export function pertanyaanAlkohol():any {
    return [
        {
            "label" : "16. Apakah anda mengonsumsi alkohol ?",
            "detail" : [
                {
                    "model" : "konsumAlkohol",
                },
                {
                    "label" : "berapa banyak dalam sehari/seminggu?",
                    "model" : "jumlahAlkohol"
                }
            ]
        },
    ]
}

export function pertanyaanPerem ():any{
    return [
        {
            "title" : "18. Untuk Perempuan",
            "detail" : [
                {
                    "label" : "Apakah siklus menstruasi anda teratur ?",
                    "model" : "MensTeratur",
                    "type" : "checkBox",
                    "value" : ["Ya","Tidak"]
                },
                {
                    "label" : "berapa lama siklus menstruasinya ?",
                    "model" : "lamaMens",
                    "type" : "textBox",
                },
                {
                    "label" : "kapan hari pertama mens terakhir anda ?",
                    "model" : "terakhirMens",
                    "type" : "textBox",
                },
                {
                    "label" : "Apakah siklus anda nyeri?",
                    "model" : "nyeriSiklus",
                    "type" : "checkBox",
                    "value" : ["Ya","Tidak"]
                },
                {
                    "label" : "Apakah anda mengonsumsi pill kontrasepsi/jenis kontrasepsi lain ?",
                    "model" : "konsumsiKonstra",
                    "type" : "checkBox",
                    "value" : ["Ya","Tidak"]
                },
                {
                    "label" : "Jenis",
                    "model" : "jenisKonstra",
                    "type" : "textBox",
                },
                {
                    "label" : "sudah berapa lama",
                    "model" : "lamaKonsum",
                    "type" : "textBox",
                },
                {
                    "label" : "Apakah anda pernah didiagnosis/diobati untuk masalah ginekologi ?",
                    "model" : "pernahDidiagnosa",
                    "type" : "checkBox",
                    "value" : ["Ya","Tidak"]
                },
            ]
        }
    ]
}