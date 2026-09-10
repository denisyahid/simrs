export function pengkajian () :any{

    return [
        {
            "title" : "Pasien mengetahui penyakit saat ini",
            "model" : "pasienMengetahui",
            "value" : ["Tahu","Tidak"],
            "optional" : {}
        },
        {
            "title" : "Sumber informasi tentang penyakit diperoleh dari",
            "model" : "informasi",
            "value" : ["Dokter","perawat","Keluarga","Lain-lain"],
            "optional" : {
                "model" : "ketInformasi",
            }
        },
        {
            "title" : "Menerima informasi jangka waktu pengobatan",
            "model" : "waktuPengobatan",
            "value" : ["Ya (dalam minggu/bulan/tahun)","Tidak"],
            "optional" : {
                "model" : "ketWaktuPengobatan",
            }
        },
        {
            "title" : "Melakukan pemeriksaan rutin :  ",
            "model" : "pemeriksaanRutin",
            "value" : ["Ya, di","Tidak"],
            "optional" : {
                "model" : "ketPemeriksaanRutin",
            }
        },
        {
            "title" : "Cara Penularan",
            "model" : "caraPenularan",
            "value" : ["Airbone","Droplet","Kontak langsung","Cairan Tubuh"],
            "optional" : {},
        },
        {
            "title" : "Penyakit Penyerta",
            "model" : "penyakitPenyerta",
            "value" : ["Ya, di","Tidak"],
            "optional" : {
                "model" : "ketPenyakitPenyerta"
            },
        },
        
    ]
}