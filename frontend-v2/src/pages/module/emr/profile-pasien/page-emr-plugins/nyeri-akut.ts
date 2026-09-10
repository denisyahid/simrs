export function Hubungan(): any {
    return[
        {
            "title": "Agen pencedera fisiologis (mis.inflamasi, iskemia, neoplasma)",
            "model": "berhubunganDengan_",
            "value": {
                "code": "APF",
                "description": "Agen pencedera fisiologis (mis.inflamasi, iskemia, neoplasma)",
            }
        },
        {
            "title": "Agen pencedera kimiawi (mis. Terbakar, bahan kimia iritan)",
            "model": "berhubunganDengan_",
            "value": {
                "code": "APK",
                "description": "Agen pencedera kimiawi (mis. Terbakar, bahan kimia iritan)",
            }
        },
        {
            "title": "Agen pencedera fisik (mis. Abses, amputasi, terbakar, terpotong, mengangkat berat, prosedur operasi, trauma, latihan fisik berlebihan)",
            "model": "berhubunganDengan_",
            "value": {
                "code": "APS",
                "description": "Agen pencedera fisik (mis. Abses, amputasi, terbakar, terpotong, mengangkat berat, prosedur operasi, trauma, latihan fisik berlebihan)",
            }
        },
    ]
}
export function dataSubjektif(): any {
    return [
        {
            "title": "Mengeluh Nyeri",
            "model": "dataSubjektif_" 
        },
    ]
}
export function dataObjektif(): any {
    return[
        {
            "title": "Tampak meringis",
            "model": "dataObjektif_" ,
            "value": {
                "code": "TM",
                "description": "Tampak meringis",
            }
        }, 
        {
            "title": "Bersikap protektif (mis. Waspada, posisi menahan nyeri)",
            "model": "dataObjektif_",
            "value": {
                "code": "BPS",
                "description": "Bersikap protektif (mis. Waspada, posisi menahan nyeri)",
            }
        }, 
        {
            "title": "Gelisah",
            "model": "dataObjektif_" ,
            "value": {
                "code": "GL",
                "description": "Gelisah",
            }

        }, 
        {
            "title": "Frekuensi nadi meningkat",
            "model": "dataObjektif_",
            "value": {
                "code": "FNM",
                "description": "Frekuensi nadi meningkat",
            } 
        }, 
        {
            "title": "Sulit tidur",
            "model": "dataObjektif_",
            "value": {
                "code": "ST",
                "description": "Sulit tidur",
            }
        }, 
        {
            "title": "Tekanan darah meningkat",
            "model": "dataObjektif_",
            "value": {
                "code": "TDM",
                "description": "Tekanan darah meningkat",
            }
        }, 
        {
            "title": "Pola nafas berubah",
            "model": "dataObjektif_",
            "value": {
                "code": "PNB",
                "description": "Pola nafas berubah",
            }
        }, 
        {
            "title": "Nafsu makan berubah",
            "model": "dataObjektif_",
            "value": {
                "code": "NMB",
                "description": "Nafsu makan berubah",
            } 
        }, 
        {
            "title": "Proses berpikir terganggu",
            "model": "dataObjektif_",
            "value": {
                "code": "PBT",
                "description": "Proses berpikir terganggu",
            }
        }, 
        {
            "title": "Menarik diri",
            "model": "dataObjektif_",
            "value": {
                "code": "MD",
                "description": "Menarik diri",
            } 
        }, 
        {
            "title": "Berfokus pada diri sendiri",
            "model": "dataObjektif_",
            "value": {
                "code": "BDS",
                "description": "Berfokus pada diri sendiri",
            }
        }, 
        {
            "title": "Diaforesis",
            "model": "dataObjektif_",
            "value": {
                "code": "DIA",
                "description": "Diaforesis",
            } 
        }, 
    ]
}
export function Tujuan(): any {
    return[
        {
            "title": "Kontrol Nyeri",
            "model": "Tujuan_",
        },
        {
            "title": "Tingkat Nyeri",
            "model": "Tujuan_" 
        },
        {
            "title": "Status Kenyamanan",
            "model": "Tujuan_" 
        }, 
    ]
}
export function kontrolNyeri() : any {
    return[
        {
            "title": "Klien mampu mengenali kapan nyeri terjadi",
            "model": "kontrolNyeri_",
            "value": {
                "code": "KM",
                "description": "Klien mampu mengenali kapan nyeri terjadi",
            } 

        },
        {
            "title": "Klien mampu mneggambarkan faktor penyebab nyeri",
            "model": "kontrolNyeri_",
            "value": {
                "code": "KMP",
                "description": "Klien mampu mneggambarkan faktor penyebab nyeri",
            }  
        },
        {
            "title": "Klien mampu melaporkan nyeri yang terkontrol",
            "model": "kontrolNyeri_",
            "value": {
                "code": "KMT",
                "description": "Klien mampu melaporkan nyeri yang terkontrol",
            } 
        },
        {
            "title": "Menggunakan analgetik yang direkomendasikan",
            "model": "kontrolNyeri_",
            "value": {
                "code": "KMR",
                "description": "Menggunakan analgetik yang direkomendasikan",
            } 
        },
        {
            "title": "Mampu menggunakan tehnik non farmakologi untuk mengurangi nyeri",
            "model": "kontrolNyeri_",
            "value": {
                "code": "MMT",
                "description": "Mampu menggunakan tehnik non farmakologi untuk mengurangi nyeri",
            } 
        },
    ]
}
export function tingkatNyeri() : any {
    return[
        {
            "title": "Mampu mengkomunikasikan kebutuhan",
            "model": "tingkatNyeri_",
            "value": {
                "code": "MKK",
                "description": "Mampu mengkomunikasikan kebutuhan",
            } 
        },
        {
            "title": "Melaporkan bahwa nyeri berkurang dengan manajemen nyeri",
            "model": "tingkatNyeri_",
            "value": {
                "code": "LNB",
                "description": "Melaporkan bahwa nyeri berkurang dengan manajemen nyeri",
            } 
        },
        {
            "title": "Dapat dukungan social dari keluarga",
            "model": "tingkatNyeri_",
            "value": {
                "code": "DDS",
                "description": "Dapat dukungan social dari keluarga",
            } 
        },
        
    ]
}
export function manajemenNyeri() : any {
    return[
        {
            "title": "Lakukan pengkajian nyeri komprehensif yang meliputi lokasi, karakteristik, onset/durasi, frekuensi, kualitas, intensitas atau  beratnya nyeri dan factor pencetus",
            "model": "manajemenNyeri_",
            "value": {
                "code": "AA",
                "description": "Lakukan pengkajian nyeri komprehensif yang meliputi lokasi, karakteristik, onset/durasi, frekuensi, kualitas, intensitas atau  beratnya nyeri dan factor pencetus",
            }  
        },
        {
            "title": "Observasi adanya petunjuk nonverbal mengenai ketidaknyamanan terutama pada mereka yang tidak dapat berkomunikasi secara efektif",
            "model": "manajemenNyeri_",
            "value": {
                "code": "BB",
                "description": "Observasi adanya petunjuk nonverbal mengenai ketidaknyamanan terutama pada mereka yang tidak dapat berkomunikasi secara efektif",
            } 
        },
        {
            "title": "Gunakan strategi komunikasi terapeutik untuk mengetahui pengalaman nyeri dan sampaikan penerimaan pasien terhadap nyeri, ",
            "model": "manajemenNyeri_",
            "value": {
                "code": "CC",
                "description": "Gunakan strategi komunikasi terapeutik untuk mengetahui pengalaman nyeri dan sampaikan penerimaan pasien terhadap nyeri",
            } 
        },
        {
            "title": "Evaluasi pengalaman nyeri masa lalu",
            "model": "manajemenNyeri_",
            "value": {
                "code": "DD",
                "description": "",
            } 
        },
        {
            "title": "Evaluai bersama pasien dan tim kesehatan lainnya, mnegenai efektifitas tindakan pengontrolan  nyeri yang pernah digunakan sebelumnya",
            "model": "manajemenNyeri_",
            "value": {
                "code": "FF",
                "description": "Evaluai bersama pasien dan tim kesehatan lainnya, mnegenai efektifitas tindakan pengontrolan  nyeri yang pernah digunakan sebelumnya",
            } 
        },
        {
            "title": "Bantu pasien dan keluarga untuk mencari dan menemukan dukungan",
            "model": "manajemenNyeri_",
            "value": {
                "code": "GG",
                "description": "Bantu pasien dan keluarga untuk mencari dan menemukan dukungan",
            } 
        },
        {
            "title": "Kendalikan faktor lingkungan yang dapat meningkatkan nyeri",
            "model": "manajemenNyeri_",
            "value": {
                "code": "HH",
                "description": "Kendalikan faktor lingkungan yang dapat meningkatkan nyeri",
            } 
        },
        {
            "title": "Dukung istirahat / tidur yang adekuat untuk mengurangi nyeri",
            "model": "manajemenNyeri_",
            "value": {
                "code": "LL",
                "description": "Dukung istirahat / tidur yang adekuat untuk mengurangi nyeri",
            } 
        },
        {
            "title": "Ajarkan metode farmakologi untuk menurunkan  nyeri",
            "model": "manajemenNyeri_",
            "value": {
                "code": "NN",
                "description": "Ajarkan metode farmakologi untuk menurunkan  nyeri",
            } 
        },
        {
            "title": "Gali penggunaan metode farmakologi yg dipakai pasien untuk mengurangi nyeri",
            "model": "manajemenNyeri_",
            "value": {
                "code": "SS",
                "description": "Gali penggunaan metode farmakologi yg dipakai pasien untuk mengurangi nyeri",
            } 
        },
        {
            "title": "Dorong pasien menggunakan obat-obatan analgetik  yang adekuat untuk mengurangi nyeri",
            "model": "manajemenNyeri_",
            "value": {
                "code": "JJ",
                "description": "Dorong pasien menggunakan obat-obatan analgetik  yang adekuat untuk mengurangi nyeri",
            } 
        },
        
    ]
}