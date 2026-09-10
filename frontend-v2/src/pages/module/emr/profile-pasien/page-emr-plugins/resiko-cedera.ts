export function HubunganEksternal(): any {
    return [
        {
            "title": "Terpapar pathogen",
            "model": "hubunganEkternal_",
            "value": {
                "code" : "TP",
                "description" : "Terpapar pathogen",
            }
        }, 
        {
            "title": "Terpapar zat kimia toksik (polutan, racun, obat, agen farmasi, alcohol, kafein nikotin, bahan pengawet, kosmetik, zat warna kain)",
            "model": "hubunganEkternal_",
            "value": {
                "code" : "TZK",
                "description" : "Terpapar zat kimia toksik (polutan, racun, obat, agen farmasi, alcohol, kafein nikotin, bahan pengawet, kosmetik, zat warna kain)",
            }
        }, 
        {
            "title": "Terpapar agen nasokomial",
            "model": "hubunganEkternal_",
            "value": {
                "code" : "TAN",
                "description" : "Terpapar agen nasokomial",
            }
        }, 
        {
            "title": "Ketidakamanan transportasi",
            "model": "hubunganDengan_",
            "value": {
                "code" : "KTI",
                "description" : "Ketidakamanan transportasi",
            }
        }, 

    ]
}
export function HubunganInternal(): any {
return[
    {
        "title": "Ketidaknormalan profil darah",
        "model": "HubunganInternal_",
        "value": {
            "code" : "KPD",
            "description" : "Ketidaknormalan profil darah",
        }
    }, 
    {
        "title": "Perubahan orientasi afektif",
        "model": "HubunganInternal_",
        "value": {
            "code" : "POA",
            "description" : "Perubahan orientasi afektif",
        }
    }, 
    {
        "title": "Perubahan sensasi",
        "model": "HubunganInternal_",
        "value": {
            "code" : "PSI",
            "description" : "Perubahan sensasi",
        }
    }, 
    {
        "title": "Disfungsi autoimun",
        "model": "HubunganInternal_",
        "value": {
            "code" : "DAU",
            "description" : "Disfungsi autoimun",
        }
    }, 
    {
        "title": "Disfungsi biokimia",
        "model": "HubunganInternal_",
        "value": {
            "code" : "DBI",
            "description" : "Disfungsi biokimia",
        }
    }, 
    {
        "title": "Hipoksia jaringan",
        "model": "HubunganInternal_",
        "value": {
            "code" : "HJA",
            "description" : "Hipoksia jaringan",
        }
    }, 
    {
        "title": "Kegagalan mekanisme pertahanan tubuh",
        "model": "HubunganInternal_",
        "value": {
            "code" : "KMP",
            "description" : "Kegagalan mekanisme pertahanan tubuh",
        }
    }, 
    {
        "title": "Malnutrisi",
        "model": "HubunganInternal_",
        "value": {
            "code" : "MLS",
            "description" : "Malnutrisi",
        }
    }, 
    {
        "title": "Perubahan fungsi psikomotor",
        "model": "HubunganInternal_",
        "value": {
            "code" : "PFP",
            "description" : "Perubahan fungsi psikomotor",
        }
    }, 
    {
        "title": "Perubahan fungsi kognitif",
        "model": "HubunganInternal_",
        "value": {
            "code" : "PFK",
            "description" : "Perubahan fungsi kognitif",
        }
    }, 
]
}
export function Tujuan(): any {
    return [
        {
            "title": "Kejadian jatuh",
            "model": "tujuan_",
            "value": {
                "code" : "",
                "description" : "Kejadian jatuh",
            }
        }, 
        {
            "title": "Kontrol resiko",
            "model": "tujuan_",
            "value": {
                "code" : "",
                "description" : "Kontrol resiko",
            }
        }, 
    ]
}
export function kejadianJatuh() : any {
    return [
        {
            "title": "Klien dapat terbebas jatuh dari tempat tidur",
            "model": "kejadianJatuh_",
            "value": {
                "code" : "KDT",
                "description" : "Klien dapat terbebas jatuh dari tempat tidur",
            }
        }, 
        {
            "title": "Klien dapat terbebas dari jatuh saat duduk, berdiri ataupun berjalan",
            "model": "kejadianJatuh_",
            "value": {
                "code" : "KDD",
                "description" : "Klien dapat terbebas dari jatuh saat duduk, berdiri ataupun berjalan",
            }
        }, 
        {
            "title": "Klien terbebas dari jatuh saat dipindahkan",
            "model": "kejadianJatuh_",
            "value": {
                "code" : "KDJ",
                "description" : "Klien terbebas dari jatuh saat dipindahkan",
            }
        }, 
        {
            "title": "Klien terbebas dari jatuh saat ke kamar mandi",
            "model": "kejadianJatuh_",
            "value": {
                "code" : "KDK",
                "description" : "Klien terbebas dari jatuh saat ke kamar mandi",
            }
        }, 
      
    ]
}

export function kontrolResiko() : any {
    return [
        {
            "title": "Klien mampu mengidentifikasi cara mencegah cedera",
            "model": "kontrolResiko_",
            "value": {
                "code" : "KMC",
                "description" : "Klien mampu mengidentifikasi cara mencegah cedera",
            }
        }, 
        {
            "title": "Klien mampu mengenali factor resiko di lingkungan",
            "model": "kontrolResiko_",
            "value": {
                "code" : "KMF",
                "description" : "Klien mampu mengenali factor resiko di lingkungan",
            }
        }, 
        {
            "title": "Klien mampu memodifikasi gaya hidup untuk mengurangi resiko",
            "model": "kontrolResiko_",
            "value": {
                "code" : "KMG",
                "description" : "Klien mampu memodifikasi gaya hidup untuk mengurangi resiko",
            }
        }, 
    ]
}

export function pencegahanJatuh() : any {
    return [
        {
            "title": "Identifikasi kekurangan baik kognitif atau fisik dari pasien yang mungkin meningkatkan potensi jatuh ",
            "model": "pencegahanJatuh_",
            "value": {
                "code" : "IKB",
                "description" : "Identifikasi kekurangan baik kognitif atau fisik dari pasien yang mungkin meningkatkan potensi jatuh ",
            }
        }, 
        {
            "title": "Kaji ulang riwayat jatuh bersama pasien dan keluarga pasien",
            "model": "pencegahanJatuh_",
            "value": {
                "code" : "KUR",
                "description" : "Kaji ulang riwayat jatuh bersama pasien dan keluarga pasien",
            }
        }, 
        {
            "title": "Bantu ambulasi individu yang memiliki ketidakseimbangan",
            "model": "pencegahanJatuh_",
            "value": {
                "code" : "BAI",
                "description" : "Bantu ambulasi individu yang memiliki ketidakseimbangan",
            }
        }, 
        {
            "title": "Letakkan benda-benda dalam jangkauan yang mudah bagi pasien",
            "model": "pencegahanJatuh_",
            "value": {
                "code" : "LBB",
                "description" : "Letakkan benda-benda dalam jangkauan yang mudah bagi pasien",
            }
        }, 
        {
            "title": "Sediakan penerangan yang cukup",
            "model": "pencegahanJatuh_",
            "value": {
                "code" : "SPC",
                "description" : "Sediakan penerangan yang cukup",
            }
        }, 
        {
            "title": "Pasang side rail tempat tidur",
            "model": "pencegahanJatuh_",
            "value": {
                "code" : "PSR",
                "description" : "Pasang side rail tempat tidur",
            }
        }, 
    ]
}
export function manajemenLingkungan(): any {
    return [
        {
            "title": "Ciptakan lingkungan yang aman bagi pasien",
            "model": "manajemenLingkungan_",
            "value": {
                "code" : "CLA",
                "description" : "Ciptakan lingkungan yang aman bagi pasien",
            }
        }, 
        {
            "title": " Identifikasi kebutuhan keselamatan pasien berdasarkan fungsi fisik dan kognitif serta riwayat perilaku di masa lalu",
            "model": "manajemenLingkungan_",
            "value": {
                "code" : "IKK",
                "description" : " Identifikasi kebutuhan keselamatan pasien berdasarkan fungsi fisik dan kognitif serta riwayat perilaku di masa lalu",
            }
        }, 
        {
            "title": "Sediakan tempat tidur dan lingkungan yang bersih dan nyaman",
            "model": "manajemenLingkungan_",
            "value": {
                "code" : "STT",
                "description" : "Sediakan tempat tidur dan lingkungan yang bersih dan nyaman",
            }
        }, 
        {
            "title": "Tempatkan saklar di posisi tempat tidur yang mudah dijangkau",
            "model": "manajemenLingkungan_",
            "value": {
                "code" : "TSD",
                "description" : "Tempatkan saklar di posisi tempat tidur yang mudah dijangkau",
            }
        }, 
        {
            "title": "Kendalikan atau cegah kebisingan bila memungkinkan",
            "model": "manajemenLingkungan_",
            "value": {
                "code" : "KAC",
                "description" : "Kendalikan atau cegah kebisingan bila memungkinkan",
            }
        }, 
        {
            "title": "Batasi pengunjung",
            "model": "manajemenLingkungan_",
            "value": {
                "code" : "BP",
                "description" : "Batasi pengunjung",
            }
        }, 
        {
            "title": "Anjurkan keluarga untuk menemani pasien",
            "model": "manajemenLingkungan_",
            "value": {
                "code" : "AKU",
                "description" : "Anjurkan keluarga untuk menemani pasien",
            }
        }, 
    ]
}
export function dataObjektif(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "Pasien gelisah",
                    "type": "checkBox",
                    "model": "pasiengelisah_",
                },
                {
                    "subTitle": "Kesadaran, GSC",
                    "type": "textBox",
                    "model": "kesadaran",
                },
                {
                    "subTitle": "Skala Resiko Jatuh",
                    "type": "textBox",
                    "model": "skalaresikojatuh",
                },
                {
                    "subTitle": "Mendapatkan Terapi",
                    "type": "textBox",
                    "model": "terapi_",
                },
                {
                    "subTitle": "Klien terpasang restrain",
                    "type": "checkBox",
                    "model": "restrain",
                },
               
            ]
        },
    ]
}