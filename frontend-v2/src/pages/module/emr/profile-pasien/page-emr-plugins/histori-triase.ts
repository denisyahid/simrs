export function listImageNyeri(): any {
    return [
        {
            "nama": "Hurts", "detail": [
                {
                    "nama": "No Hurt", "descNilai": 0,
                    "img": "/images/skalanyeri/1.png"
                },
                {
                    "nama": "Hurts Little Bit", "descNilai": 2,
                    "img": "/images/skalanyeri/2.png",
                },
                {
                    "nama": "Hurts Little More", "descNilai": 4,
                    "img": "/images/skalanyeri/3.png",
                },
                {
                    "nama": "Hurts Even More", "descNilai": 6,
                    "img": "/images/skalanyeri/4.png",
                },
                {
                    "nama": "Hurts Whole Lot", "descNilai": 8,
                    "img": "/images/skalanyeri/5.png",
                },
                {
                    "nama": "Hurts whorts", "descNilai": 10,
                    "img": "/images/skalanyeri/6.png",
                }]
        }
    ]
}

export function listSkoringNyeri(): any {
    return [
        {
            "nama": "Score ", "detail": [
                { "nama": "0 - 1 = Tidak Ada Nyeri", "descNilai": 0 },
                { "nama": "2 - 3 = Sedikit Nyeri", "descNilai": 2 },
                { "nama": "4 - 5 = Cukup Nyeri", "descNilai": 4 },
                { "nama": "6 - 7 = Lumayan Nyeri", "descNilai": 6 },
                { "nama": "8 - 9 = Sangat Nyeri", "descNilai": 8 },
                { "nama": "10 = Amat Sangat Nyeri", "descNilai": 10 },
            ]
        }

    ]
}
export function daftarList(): any {
    return [
        {
            "Kriteria": "Jalan Napas",
            "kolom1": [
                {
                    "label": "Sumbatan",
                    "model": "jalanNapas_"
                },
            ],  
            "kolom2": [
                {
                    "label": "Bebas",
                    "model": "jalanNapas1_"
                },
            ],  
            "kolom3": [
                {
                    "label": "Bebas",
                    "model": "jalanNapas2_"
                },
            ],  
            "kolom4": [
                {
                    "label": "Ancaman Sumbatan",
                    "model": "jalanNapas3_"
                },
            ],  
            "keterangan": [
                {
                    "label": "Pernapasan",
                    "model" : "pernapasan",
                    "type" : "textBox",
                }
            ],        
        },
        {
            "Kriteria": "Pernapasan",
            "kolom1": [
                {
                    "label": "Henti Napas",
                    "model": "pernapasan_"
                },
                {
                    "label": "Napas (< 10x/mnt)",
                    "model": "pernapasan_"
                },
                {
                    "label": "Napas (> 32x/mnt) ",
                    "model": "pernapasan_"
                },
            ],  
            "kolom2": [
                {
                    "label": "Napas (25 - 32 x/mnt)",
                    "model": "pernapasan1_"
                },
                {
                    "label": " Whezing/Mengi",
                    "model": "pernapasan1_"
                },
            ],  
           
            "kolom3": [
                {
                    "label": "Normal",
                    "model": "pernapasan2_"
                },
                {
                    "label": "Napas (10 -24 x/mnt)",
                    "model": "pernapasan2_"
                },
              
            ],  
            "kolom4": [
                {
                    "label": "Henti Napas",
                    "model": "pernapasan3_"
                },
            ],  
            "keterangan": [
                {
                    "label": "Nadi",
                    "model" : "nadi",
                    "type" : "textBox",
                }
            ],        
        },
        {
            "Kriteria": "Sirkulalsi",
            "kolom1": [
                {
                    "label": "Henti Jantung",
                    "model": "sirkulasi_"
                },
                {
                    "label": "Nadi Lemah",
                    "model": "sirkulasi_"
                },
                {
                    "label": "Nadi (< 50x/mnt) ",
                    "model": "sirkulasi_"
                },
                {
                    "label": "Nadi (< 120x/mnt) ",
                    "model": "sirkulasi_"
                },
                {
                    "label": "Akral Dingin",
                    "model": "sirkulasi_"
                },
                {
                    "label": "CRT > 2 detik",
                    "model": "sirkulasi_"
                },
                {
                    "label": "Nyeri Dada (Iskemik)",
                    "model": "sirkulasi_"
                },
            ],  
            "kolom2": [
                {
                    "label": "Nadi Kuat",
                    "model": "sirkulasi1_"
                },
                {
                    "label": "Nadi (110 - 120x/mnt)",
                    "model": "sirkulasi1_"
                },
                {
                    "label": "Nadi (51 - 59x/mnt)",
                    "model": "sirkulasi1_"
                },
                {
                    "label": "Akral Hangat",
                    "model": "sirkulasi1_"
                },
                {
                    "label": "CRT < 2 detik",
                    "model": "sirkulasi1_"
                },
                {
                    "label": "Skala Nyeri >= 4",
                    "model": "sirkulasi1_"
                },
                {
                    "label": "Sistol > 160 mmHg",
                    "model": "sirkulasi1_"
                },
                {
                    "label": "Diastol > 100 mmHg",
                    "model": "sirkulasi1_"
                },
               
                
            ],  
            "kolom3": [
                {
                    "label": "Nadi Kuat",
                    "model": "sirkulasi2_"
                },
                {
                    "label": "Nadi (60 - 100 x/mnt)",
                    "model": "sirkulasi2_"
                },
                {
                    "label": "Akral Hangat",
                    "model": "sirkulasi2_"
                },
                {
                    "label": "CRT < 2 detik",
                    "model": "sirkulasi2_"
                },
                {
                    "label": "Skala Nyeri <= 3",
                    "model": "sirkulasi2_"
                },
            ],  
            "kolom4": [
                {
                    "label": "Henti Jantung",
                    "model": "sirkulasi3_"
                },
                {
                    "label": "EKG Asistol",
                    "model": "sirkulasi3_"
                },
            ],  
            "keterangan": [
                {
                    "label": "Tekanan Darah",
                    "model" : "tekananDarah",
                    "type" : "textBox",
                },
                {
                    "label": "Suhu",
                    "model" : "suhu",
                    "type" : "textBox",
                },
                {
                    "label": "Saturasi O2",
                    "model" : "saturasi",
                    "type" : "textBox",
                }
            ],        
        },
    ]
}