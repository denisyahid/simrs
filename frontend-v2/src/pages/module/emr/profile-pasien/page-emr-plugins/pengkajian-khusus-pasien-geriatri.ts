
export function dataTable(): any {
    return [
        {
            "no" : 1,
            "paramter" : "Makan",
            "model" : "makan",
            "pengkajian" : [
                {
                    "title" : "Tidak Mampu",
                    "poin" : 0,
                    "value" : {
                        "code" : "TM",
                        "poin" : 0,
                        "description" : "Tidak Mampu"
                    },
                    
                },
                {
                    "title" : "Memerlukan bantuan seperti, mengoleskan mentega, atau memerlukan bentuk diet khusus",
                    "poin" : 5,
                    "value" : {
                        "code" : "MB",
                        "poin" : 5,
                        "description" : "Memerlukan bantuan seperti, mengoleskan mentega, atau memerlukan bentuk diet khusus"
                    }
                },
                {
                    "title" : "Mandiri / Tanpa Bantuan",
                    "poin" : 10,
                    "value" : {
                        "code" : "TB",
                        "poin" : 10,
                        "description" : "Mandiri / Tanpa Bantuan"
                    }
                },
            ]
        },
        {
            "no" : 2,
            "paramter" : "Mandi",
            "model" : "mandi",
            "pengkajian" : [
                {
                    "title" : "Tergantung",
                    "poin" : 0,
                    "value" : {
                        "code" : "TG",
                        "poin" : 0,
                        "description" : "Tidak Mampu"
                    },
                    
                },
                {
                    "title" : "Mandiri",
                    "poin" : 5,
                    "value" : {
                        "code" : "MI",
                        "poin" : 5,
                        "description" : "Mandiri"
                    }
                },
            ]
        },
        {
            "no" : 3,
            "paramter" : "Kerapihan / Penampilan",
            "model" : "kerapihan",
            "pengkajian" : [
                {
                    "title" : "Memerlukan bantuan untuk  menata penampilan diri",
                    "poin" : 0,
                    "value" : {
                        "code" : "MBP",
                        "poin" : 0,
                        "description" : "Memerlukan bantuan untuk  menata penampilan diri"
                    },
                    
                },
                {
                    "title" : "Mandiri (Mampu menyikat gigi, mengelap wajah, menata rambut, bercukur)",
                    "poin" : 5,
                    "value" : {
                        "code" : "MI",
                        "poin" : 5,
                        "description" : "Mandiri (Mampu menyikat gigi, mengelap wajah, menata rambut, bercukur)"
                    }
                },
            ]
        },
        {
            "no" : 4,
            "paramter" : "Berpakaian",
            "model" : "berpakaian",
            "pengkajian" : [
                {
                    "title" : "Tergantung / tidak mampu",
                    "poin" : 0,
                    "value" : {
                        "code" : "TM",
                        "poin" : 0,
                        "description" : "Memerlukan bantuan untuk  menata penampilan diri"
                    },
                    
                },
                {
                    "title" : "Mandiri (mampu mengancingkan baju, menutup resleting)",
                    "poin" : 5,
                    "value" : {
                        "code" : "MI",
                        "poin" : 5,
                        "description" : "Mandiri (mampu mengancingkan baju, menutup resleting)"
                    }
                },
            ]
        },
        {
            "no" : 5,
            "paramter" : "Buang Air Besar",
            "model" : "BAB",
            "pengkajian" : [
                {
                    "title" : "Inkontinensia",
                    "poin" : 0,
                    "value" : {
                        "code" : "IA",
                        "poin" : 0,
                        "description" : "Inkontinensia"
                    },
                    
                },
                {
                    "title" : "Kadang mengalami kesulitan",
                    "poin" : 5,
                    "value" : {
                        "code" : "KMK",
                        "poin" : 5,
                        "description" : "Kadang mengalami kesulitan"
                    }
                },
                {
                    "title" : "Mandiri",
                    "poin" : 10,
                    "value" : {
                        "code" : "MI",
                        "poin" : 10,
                        "description" : "Mandiri"
                    }
                },
            ]
        },
        {
            "no" : 6,
            "paramter" : "Buang Air Kecil",
            "model" : "BAK",
            "pengkajian" : [
                {
                    "title" : "Inkontinensia, harus dipasang kateter, tidak mampu mengontrol BAK secara mandiri",
                    "poin" : 0,
                    "value" : {
                        "code" : "IA",
                        "poin" : 0,
                        "description" : "Inkontinensia, harus dipasang kateter, tidak mampu mengontrol BAK secara mandiri"
                    },
                    
                },
                {
                    "title" : " Kadang mengalami kesulitan",
                    "poin" : 5,
                    "value" : {
                        "code" : "KMK",
                        "poin" : 5,
                        "description" : " Kadang mengalami kesulitan"
                    }
                },
                {
                    "title" : "Mandiri",
                    "poin" : 10,
                    "value" : {
                        "code" : "MI",
                        "poin" : 10,
                        "description" : "Mandiri"
                    }
                },
            ]
        },
        {
            "no" : 7,
            "paramter" : "Penggunaan kamar mandi / toilet",
            "model" : "toilet",
            "pengkajian" : [
                {
                    "title" : "Tergantung",
                    "poin" : 0,
                    "value" : {
                        "code" : "TG",
                        "poin" : 0,
                        "description" : "Tergantung"
                    },
                    
                },
                {
                    "title" : "Perlu digantung tapi tidak tergantung penuh",
                    "poin" : 5,
                    "value" : {
                        "code" : "PDP",
                        "poin" : 5,
                        "description" : "Perlu digantung tapi tidak tergantung penuh"
                    }
                },
                {
                    "title" : "Mandiri",
                    "poin" : 10,
                    "value" : {
                        "code" : "MI",
                        "poin" : 10,
                        "description" : "Mandiri"
                    }
                },
            ]
        },
        {
            "no" : 8,
            "paramter" : "Berpindah tempat (dari tempat tidur ke tempat duduk atau sebaliknya)",
            "model" : "berpindahTempat",
            "pengkajian" : [
                {
                    "title" : "Tidak mampu, mengalami gangguan keseimbangan",
                    "poin" : 0,
                    "value" : {
                        "code" : "TM",
                        "poin" : 0,
                        "description" : "Tidak mampu, mengalami gangguan keseimbangan"
                    },
                    
                },
                {
                    "title" : "Memerlukan bantuan (perlu satu atau dua orang) untuk bisa duduk",
                    "poin" : 5,
                    "value" : {
                        "code" : "MB",
                        "poin" : 5,
                        "description" : "Memerlukan bantuan (perlu satu atau dua orang) untuk bisa duduk"
                    }
                },
                {
                    "title" : "Memerlukan sedikit bantuan (hanya diarahkan secara verbal)",
                    "poin" : 10,
                    "value" : {
                        "code" : "MSB",
                        "poin" : 10,
                        "description" : "Memerlukan sedikit bantuan (hanya diarahkan secara verbal)"
                    }
                },
            ]
        },
        {
            "no" : 9,
            "paramter" : "Mobilitas (berjalan pada permukaan yang rata)",
            "model" : "mobilitas",
            "pengkajian" : [
                {
                    "title" : "Tidak mampu atau berjalan kurang dari 50 meter",
                    "poin" : 0,
                    "value" : {
                        "code" : "TMB",
                        "poin" : 0,
                        "description" : "Tidak mampu atau berjalan kurang dari 50 meter"
                    },
                    
                },
                {
                    "title" : "Hanya bisa bergerak dengan kursi roda, lebih dari 50 meter",
                    "poin" : 5,
                    "value" : {
                        "code" : "HBK",
                        "poin" : 5,
                        "description" : "Hanya bisa bergerak dengan kursi roda, lebih dari 50 meter"
                    }
                },
                {
                    "title" : "Berjalan dengan bantuan lenih dari 50 meter",
                    "poin" : 10,
                    "value" : {
                        "code" : "BDB",
                        "poin" : 10,
                        "description" : "Berjalan dengan bantuan lenih dari 50 meter"
                    }
                },
                {
                    "title" : "Mandiri (meski menggunakan alat bantu)",
                    "poin" : 15,
                    "value" : {
                        "code" : "MI",
                        "poin" : 15,
                        "description" : "Mandiri (meski menggunakan alat bantu)"
                    }
                },
            ]
        },
        {
            "no" : 10,
            "paramter" : "Menaiki / menuruni tangga",
            "model" : "menaikiTangga",
            "pengkajian" : [
                {
                    "title" : "Tidak mampu",
                    "poin" : 0,
                    "value" : {
                        "code" : "TM",
                        "poin" : 0,
                        "description" : "Tidak mampu"
                    },
                    
                },
                {
                    "title" : "Memerlukan bantuan",
                    "poin" : 5,
                    "value" : {
                        "code" : "MB",
                        "poin" : 5,
                        "description" : "Memerlukan bantuan"
                    }
                },
                {
                    "title" : "Mandiri",
                    "poin" : 10,
                    "value" : {
                        "code" : "MI",
                        "poin" : 10,
                        "description" : "Mandiri"
                    }
                },
               
            ]
        },
    ]
}

export function listDescriptionPoin () : any{
    return [
        {
            "model" : "ketPoin",
            "title" : "100 = Mandiri",
            "value" : {
                "poin" : 100,
                "title" : "100 = Mandiri",
            }
        },
        {
            "model" : "ketPoin",
            "title" : "91 - 99 = Ketergantungan Ringan",
            "value" : {
                "poin" : 99,
                "title" : "91 - 99 = Ketergantungan Ringan",
            }
        },
        {
            "model" : "ketPoin",
            "title" : "61 - 90 = Ketergantungan Sedang",
            "value" : {
                "poin" : 90,
                "title" : "61 - 90 = Ketergantungan Sedang",
            }
        },
        {
            "model" : "ketPoin",
            "title" : "21 - 60 = Ketergantungan Berat",
            "value" : {
                "poin" : 60,
                "title" : "21 - 60 = Ketergantungan Berat",
            }
        },
        {
            "model" : "ketPoin",
            "title" : "0 - 20 = Ketergantungan Total",
            "value" : {
                "poin" : 20,
                "title" : "0 - 20 = Ketergantungan Total",
            }
        },
        
    ]
}