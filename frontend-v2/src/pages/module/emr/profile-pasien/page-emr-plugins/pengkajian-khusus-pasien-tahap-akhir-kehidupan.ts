export function asesmenMedik () :any {
    return [
        {
            "title" : "1. Gejala-gejala",
            "detail" : [
                {
                    "subTitle" : "a. Kegawatan Pernapasan",
                    "value" : [
                        {
                            "model" : "kegawatanPernapasan_",
                            "label" : "Mukosa oral kering",
                            "value" : {
                                "code" : "MOK",
                                "descript" : "Mukosa oral kering"
                            },
                        },
                        {
                            "model" : "kegawatanPernapasan_",
                            "label" : "Ada secret",
                            "value" : {
                                "code" : "AC",
                                "descript" : "Ada secret"
                            },
                        },
                        {
                            "model" : "kegawatanPernapasan_",
                            "label" : "Dyspneu",
                            "value" : {
                                "code" : "DU",
                                "descript" : "Dyspneu"
                            },
                        },
                        {
                            "model" : "kegawatanPernapasan_",
                            "label" : "Tidak ada kelainan",
                            "value" : {
                                "code" : "DAK",
                                "descript" : "Tidak ada kelainan"
                            },
                        },
                        {
                            "model" : "kegawatanPernapasan_",
                            "label" : "Napas melalui mulut",
                            "value" : {
                                "code" : "NMM",
                                "descript" : "Napas melalui mulut"
                            },
                        },
                        {
                            "model" : "kegawatanPernapasan_",
                            "label" : "Napas lambat",
                            "value" : {
                                "code" : "NL",
                                "descript" : "Napas lambat"
                            },
                        },
                        {
                            "model" : "kegawatanPernapasan_",
                            "label" : "SpO2 < normal",
                            "value" : {
                                "code" : "spo2",
                                "descript" : "SpO2 < normal"
                            },
                        },
                        {
                            "model" : "kegawatanPernapasan_",
                            "label" : "Napas tidak teratur",
                            "value" : {
                                "code" : "NTT",
                                "descript" : "Napas tidak teratur"
                            },
                        },
                        {
                            "model" : "kegawatanPernapasan_",
                            "label" : "Napas cepat dan dangkal",
                            "value" : {
                                "code" : "NTT",
                                "descript" : "Napas cepat dan dangkal"
                            },
                        },
                    ],
                    "optional" : "",
                },
                {
                    "subTitle" : "b. Kehilangan tonus otot",
                    "value" : [
                        {
                            "model" : "kehilanganTonusOtot_",
                            "label" : "Inkontinensia fases",
                            "value" : {
                                "code" : "IF",
                                "descript" : "Inkontinensia fases"
                            },
                        },
                        {
                            "model" : "kehilanganTonusOtot_",
                            "label" : "Sulit menelan",
                            "value" : {
                                "code" : "SM",
                                "descript" : "Sulit menelan"
                            },
                        },
                        {
                            "model" : "kehilanganTonusOtot_",
                            "label" : "Mual",
                            "value" : {
                                "code" : "ML",
                                "descript" : "Mual"
                            },
                        },
                        {
                            "model" : "kehilanganTonusOtot_",
                            "label" : "Penurunan pergerakan tubuh",
                            "value" : {
                                "code" : "PPT",
                                "descript" : "Penurunan pergerakan tubuh"
                            },
                        },
                        {
                            "model" : "kehilanganTonusOtot_",
                            "label" : "Inkontinensia urine",
                            "value" : {
                                "code" : "IU",
                                "descript" : "Inkontinensia urine"
                            },
                        },
                        {
                            "model" : "kehilanganTonusOtot_",
                            "label" : "Distensi abdomen",
                            "value" : {
                                "code" : "DA",
                                "descript" : "Distensi abdomen"
                            },
                        },
                        {
                            "model" : "kehilanganTonusOtot_",
                            "label" : "Sulit bicara",
                            "value" : {
                                "code" : "SB",
                                "descript" : "Sulit bicara"
                            },
                        },
                        {
                            "model" : "kehilanganTonusOtot_",
                            "label" : "Tidak ada kelainan",
                            "value" : {
                                "code" : "TAK",
                                "descript" : "Tidak ada kelainan"
                            },
                        },
                    ],
                    "optional" : "",
                },
                {
                    "subTitle" : "c. Nyeri",
                    "value" : [
                        {
                            "model" : "nyeri_",
                            "label" : "Tidak",
                            "value" : {
                                "code" : "tidak",
                                "descript" : "Tidak"
                            },
                        },
                        {
                            "model" : "nyeri_",
                            "label" : "Ya",
                            "value" : {
                                "code" : "ya",
                                "descript" : "YA"
                            },
                        },
                        {
                            "model" : "nyeri_",
                            "label" : "Lokasi",
                            "value" : {
                                "code" : "lokasi",
                                "descript" : "Lokasi"
                            },
                        },
                    ],
                    "optional" : {
                        "model" : "ketLokasi"
                    },
                },
                {
                    "subTitle" : "d. Perlambatan sirkulasi",
                    "value" : [
                        {
                            "model" : "perlambatanSirklus_",
                            "label" : "Bercak dan Sianosis pada ekstremitas",
                            "value" : {
                                "code" : "BSE",
                                "descript" : "Bercak dan Sianosis pada ekstremitas"
                            },
                        },
                        {
                            "model" : "perlambatanSirklus_",
                            "label" : "Gelisah",
                            "value" : {
                                "code" : "GH",
                                "descript" : "Gelisah"
                            },
                        },
                        {
                            "model" : "perlambatanSirklus_",
                            "label" : "Lemas",
                            "value" : {
                                "code" : "LS",
                                "descript" : "Lemas"
                            },
                        },
                    ],
                    "optional" : "",
                },
                {
                    "subTitle" : "e. Pencernaan",
                    "value" : [
                        {
                            "model" : "pencernaan_",
                            "label" : "Mual",
                            "value" : {
                                "code" : "ML",
                                "descript" : "Mual"
                            },
                        },
                        {
                            "model" : "pencernaan_",
                            "label" : "Muntah",
                            "value" : {
                                "code" : "MH",
                                "descript" : "Muntah"
                            },
                        },
                        {
                            "model" : "pencernaan_",
                            "label" : "Lainnya",
                            "value" : {
                                "code" : "LA",
                                "descript" : "Lainnya"
                            },
                        },
                    ],
                    "optional" : {
                        "model" : "ketPencernaan"
                    },
                },
            ]
        },
        {
            "title" : "2. Faktor-faktor yang meningkatkan dan membangkitkan gejala fisik",
            "detail" : [
                {
                    "subTitle" : "",
                    "value" : [
                        {
                            "model" : "foktorPenyebab_",
                            "label" : "Melakukan aktifitas fisik",
                            "value" : {
                                "code" : "MAF",
                                "descript" : "Melakukan aktifitas fisik",
                            }
                        },
                        {
                            "model" : "foktorPenyebab_",
                            "label" : "Pindah posisi",
                            "value" : {
                                "code" : "PP",
                                "descript" : "Pindah posisi",
                            }
                        },
                        {
                            "model" : "foktorPenyebab_",
                            "label" : "Lainnya",
                            "value" : {
                                "code" : "PP",
                                "descript" : "Lainnya",
                            }
                        },
                    ],
                    "optional" : {
                        "model" : "ketFaktor"
                    }
                }
            ]
        }
    ]
}

export function asesmenKeperawatan () :any{
    return [
        {
            "title" : "1. Orientasi spiritual pasien dan keluarga",
            "detail" : [
                {
                    "subTitle" : "Apakah perlu pelayanan dan keluarga ?",
                    "value" : [
                        {
                            "label" : "Ya, Oleh",
                            "model" : "pelayanan_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "YA",
                                "descrip" : "Ya, Oleh"
                            }
                        },
                        {
                            "label" : "",
                            "model" : "ketPelayanan_",
                            "type" : "textBox",
                        },
                        {
                            "label" : "Tidak",
                            "model" : "pelayanan_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "TK",
                                "descrip" : "Tidak"
                            }
                        },
                    ]
                }
            ]
        },
        {
            "title" : "2. Urusan dan kebutuhan spiritual pasien dan keluarga seperti putus asa dan penderitaan rasa bersalah atau pengampunan",
            "detail" : [
                {
                    "subTitle" : "Perlu didoakan",
                    "value" : [
                        {
                            "label" : "Ya, Oleh",
                            "model" : "doa_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "YA",
                                "descrip" : "Ya, Oleh"
                            }
                        },
                        {
                            "label" : "",
                            "model" : "ketDoa_",
                            "type" : "textBox",
                        },
                        {
                            "label" : "Tidak",
                            "model" : "doa_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "TK",
                                "descrip" : "Tidak"
                            }
                        },
                    ]
                },
                {
                    "subTitle" : "Perlu bimbingan rohani",
                    "value" : [
                        {
                            "label" : "Ya, Oleh",
                            "model" : "bimbinganRohan",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "YA",
                                "descrip" : "Ya, Oleh"
                            }
                        },
                        {
                            "label" : "",
                            "model" : "ketBimbinganRohan",
                            "type" : "textBox",
                        },
                        {
                            "label" : "Tidak",
                            "model" : "bimbinganRohan",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "TK",
                                "descrip" : "Tidak"
                            }
                        },
                    ]
                },
                {
                    "subTitle" : "Perlu pendampingan rohani",
                    "value" : [
                        {
                            "label" : "Ya, Oleh",
                            "model" : "pendampingRohani",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "YA",
                                "descrip" : "Ya, Oleh"
                            }
                        },
                        {
                            "label" : "",
                            "model" : "ketPendampingRohani",
                            "type" : "textBox",
                        },
                        {
                            "label" : "Tidak",
                            "model" : "pendampingRohani",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "TK",
                                "descrip" : "Tidak"
                            }
                        },
                    ]
                },
            ]
        },
        {
            "title" : "3. Status psikososial pasien dan keluarga",
            "detail" : [
                {
                    "subTitle" : "Apakah ada orang yang ingin dihubungi saat ini",
                    "value" : [
                        {
                            "label" : "Tidak",
                            "model" : "hubungiOrang_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "TK",
                                "descrip" : "Tidak"
                            }
                        },
                        {
                            "label" : "Ya",
                            "model" : "hubungiOrang_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "Ya",
                                "descrip" : "Tidak"
                            }
                        },
                        {
                            "label" : "",
                            "model" : "ketHubungiOrang",
                            "type" : "textBox",
                        },        
                    ]
                },
                {
                    "subTitle" : "Hubungan dengan pasien",
                    "value" : [
                        {
                            "label" : "",
                            "model" : "ketHubunganPasien",
                            "type" : "textBox",
                        },        
                    ]
                },
            ]
        },
        {
            "title" : "4. Reaksi pasien atas penyakitnya",
            "detail" : [
                {
                    "subTitle" : "",
                    "value" : [
                        {
                            "label" : "Menyangkal",
                            "model" : "reaksiPasien_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "ML",
                                "descrip" : "Menyangkal"
                            }
                        },
                        {
                            "label" : "Marah",
                            "model" : "reaksiPasien_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "MH",
                                "descrip" : "Marah"
                            }
                        },
                        {
                            "label" : "Sedih / Menangis",
                            "model" : "reaksiPasien_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "SM",
                                "descrip" : "Sedih / Menangis"
                            }
                        },
                        {
                            "label" : "Takut",
                            "model" : "reaksiPasien_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "TT",
                                "descrip" : "Takut"
                            }
                        },
                    ]
                }
            ]
        },
        {
            "title" : "5. Reaksi keluarga atas penyakit pasien",
            "detail" : [
                {
                    "subTitle" : "",
                    "value" : [
                        {
                            "label" : "Marah",
                            "model" : "reaksiKeluarga_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "MH",
                                "descrip" : "Marah"
                            }
                        },
                        {
                            "label" : "Ganguan tidur",
                            "model" : "reaksiKeluarga_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "GT",
                                "descrip" : "Ganguan tidur"
                            }
                        },
                        {
                            "label" : "Penurunan Kesadaran",
                            "model" : "reaksiKeluarga_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "PK",
                                "descrip" : "Penurunan Kesadaran"
                            }
                        },
                        {
                            "label" : "Rasa bersalah",
                            "model" : "reaksiKeluarga_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "RB",
                                "descrip" : "Rasa bersalah"
                            }
                        },
                        {
                            "label" : "Keluarga tidak berkomunikasi dengan pasien",
                            "model" : "reaksiKeluarga_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "KBP",
                                "descrip" : "Keluarga tidak berkomunikasi dengan pasien"
                            }
                        },
                        {
                            "label" : "Letih",
                            "model" : "reaksiKeluarga_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "LH",
                                "descrip" : "Letih"
                            }
                        },
                        {
                            "label" : "Lelah",
                            "model" : "reaksiKeluarga_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "LL",
                                "descrip" : "Lelah"
                            }
                        },
                        {
                            "label" : "Menangis",
                            "model" : "reaksiKeluarga_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "MS",
                                "descrip" : "Menangis"
                            }
                        },
                        {
                            "label" : "Sedih",
                            "model" : "reaksiKeluarga_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "SH",
                                "descrip" : "Sedih"
                            }
                        },
                        {
                            "label" : "Ketidakmampuan memenuhi peran yang diharapkan",
                            "model" : "reaksiKeluarga_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "KMP",
                                "descrip" : "Ketidakmampuan memenuhi peran yang diharapkan"
                            }
                        },
                    ]
                }
            ]
        },
        {
            "title" : "6. Kebutuhan dukungan atau pelonggaran pelayanan bagi pasien, keluarga dan pemberi pelayanan",
            "detail" : [
                {
                    "subTitle" : "",
                    "value" : [
                        {
                            "label" : "Pasien perlu didampingi keluarga",
                            "model" : "kebutuhanDukungan_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "PPDK",
                                "descrip" : "Pasien perlu didampingi keluarga"
                            }
                        },
                        {
                            "label" : "Keluarga / sahabat dapat melihat pasien diluar waktu berkunjung",
                            "model" : "kebutuhanDukungan_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "KSPB",
                                "descrip" : "Keluarga / sahabat dapat melihat pasien diluar waktu berkunjung"
                            }
                        },
                    ]
                }
            ]
        },
        {
            "title" : "7. Apakah ada kebutuhan atau alternative pelayanan lainnya",
            "detail" : [
                {
                    "subTitle" : "",
                    "value" : [
                        {
                            "label" : "Tidak",
                            "model" : "alternativePelayanan_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "PPDK",
                                "descrip" : "Tidak"
                            }
                        },
                        {
                            "label" : "Donasi organ",
                            "model" : "alternativePelayanan_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "KSPB",
                                "descrip" : "Donasi organ"
                            }
                        },
                        {
                            "label" : "",
                            "model" : "ketAlternativePel",
                            "type" : "textBox",
                            "value" : {
                                "code" : "KSPB",
                                "descrip" : "Donasi organ"
                            }
                        },
                    ]
                }
            ]
        },
        {
            "title" : "8. Faktor resiko bagi keluarga yang ditinggalkan",
            "detail" : [
                {
                    "subTitle" : "Asesmen Informasi",
                    "value" : [
                        {
                            "label" : "Marah",
                            "model" : "faktorResiko_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "PPDK",
                                "descrip" : "Marah"
                            }
                        },
                        {
                            "label" : "Depresi",
                            "model" : "faktorResiko_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "KSPB",
                                "descrip" : "Depresi"
                            }
                        },
                        {
                            "label" : "Rasa Bersalah",
                            "model" : "faktorResiko_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "PPDK",
                                "descrip" : "Rasa Bersalah"
                            }
                        },
                        {
                            "label" : "Perubahan kebiasaan pola komunikasi",
                            "model" : "faktorResiko_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "KSPB",
                                "descrip" : "Perubahan kebiasaan pola komunikasi"
                            }
                        },
                        {
                            "label" : "Ketidakmampuan memenuhi peran yang diharapkan",
                            "model" : "faktorResiko_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "PPDK",
                                "descrip" : "Ketidakmampuan memenuhi peran yang diharapkan"
                            }
                        },
                        
                    ]
                }
            ]
        },

        {
            "title" : "9. Masalah keperawatan",
            "detail" : [
                {
                    "subTitle" : "Asesmen Informasi",
                    "value" : [
                        {
                            "label" : "Mual",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "ML",
                                "descrip" : "Mual"
                            }
                        },
                        {
                            "label" : "Nyeri Kronis",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "NK",
                                "descrip" : "Nyeri Kronis"
                            }
                        },
                        {
                            "label" : "Nyeri Akut",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "NA",
                                "descrip" : "Nyeri Akut"
                            }
                        },
                        {
                            "label" : "Ansietas",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "AS",
                                "descrip" : "Ansietas"
                            }
                        },
                        {
                            "label" : "Kematian",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "KN",
                                "descrip" : "Kematian"
                            }
                        },
                        {
                            "label" : "Pola napas tidak efektif",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "PN",
                                "descrip" : "Pola napas tidak efektif"
                            }
                        },
                        {
                            "label" : "Konstipasi",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "KI",
                                "descrip" : "Konstipasi"
                            }
                        },
                        {
                            "label" : "Distress spiritual",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "DS",
                                "descrip" : "Distress spiritual"
                            }
                        },
                        {
                            "label" : "Defisit perawatan dari",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "DP",
                                "descrip" : "Defisit perawatan dari"
                            }
                        },
                        {
                            "label" : "Perubahan proses keluarga",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "PPK",
                                "descrip" : "Perubahan proses keluarga"
                            }
                        },
                        {
                            "label" : "Kopping individu tidak efektif",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "KI",
                                "descrip" : "Kopping individu tidak efektif"
                            }
                        },
                        {
                            "label" : "Perubahan persepsi",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "PP",
                                "descrip" : "Perubahan persepsi"
                            }
                        },
                        {
                            "label" : "Bersihan jalan napas tidak efektif",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "BJN",
                                "descrip" : "Bersihan jalan napas tidak efektif"
                            }
                        },
                        {
                            "label" : "Lainnya",
                            "model" : "masalahKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "KSPB",
                                "descrip" : "Lainnya"
                            }
                        },
                        {
                            "label" : "",
                            "model" : "ketMasalahKeper_",
                            "type" : "textBox",
                        },
                    ]
                }
            ]
        },

        {
            "title" : "10. Bagaimana rencana keperawatan selanjutnya",
            "detail" : [
                {
                    "subTitle" : "",
                    "value" : [
                        {
                            "label" : "Tetap dirawat diruangan intensif / ruangan perawat",
                            "model" : "rencanaKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "TDI",
                                "descrip" : "Tetap dirawat diruangan intensif / ruangan perawat"
                            }
                        },
                        {
                            "label" : "Dirawat dirumah",
                            "model" : "rencanaKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "DD",
                                "descrip" : "Dirawat dirumah"
                            }
                        },
                        {
                            "label" : "Apakah lingkungan rumah sudah disiapkan?",
                            "model" : "rencanaKeper_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "LRS",
                                "descrip" : "Apakah lingkungan rumah sudah disiapkan?"
                            }
                        },
                    ]
                },
                {
                    "subTitle" : "Jika ya, apakah ada yang mampu merawat pasien dirumah?",
                    "value" : [
                        {
                            "label" : "Ya, Oleh",
                            "model" : "dirawatOleh_",
                            "type" : "checkBox",
                            "value" : {
                                "code" : "YA",
                                "descrip" : "Ya, Oleh"
                            }
                        },
                        {
                            "label" : "",
                            "model" : "ketDirawatOleh",
                            "type" : "textBox",
                        },
                    ]
                },
                {
                    "subTitle" : "Jika tidak, apakah ada perlu difasilitasi oleh rumah sakit",
                    "value" : [
                        {
                            "label" : "",
                            "model" : "ketFasilitasRs",
                            "type" : "textBox",
                        },
                    ]
                },
                {
                    "subTitle" : "Pesan Terakhir Pasien",
                    "value" : [
                        {
                            "label" : "",
                            "model" : "pesanTerakhirPas",
                            "type" : "textArea",
                        },
                    ]
                },
            ]
        },

    ]
}