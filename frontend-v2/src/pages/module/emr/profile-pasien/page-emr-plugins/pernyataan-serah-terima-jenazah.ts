export function jenazah():any{
    return ['Suami','Istri','Anak','Orangtua','Wali','Keluarga']
}

export function pengenalJenazah():any{
    return [
        {
            "label" : "Saya",
            "model" : "namaPenerima",
            "type" : "textBox"
        },
        {
            "label" : "Nama Pasien",
            "model" : "namaPasien",
            "type" : "textBox"
        },
        {
            "label" : "Jenis Kelamin",
            "model" : "jenisKlmPasien",
            "type" : "textBox"
        },
        {
            "label" : "No. RM",
            "model" : "nocm",
            "type" : "textBox"
        },
        {
            "label" : "Tanggal Lahir",
            "model" : "tglLahirPasien",
            "type" : "date"
        },
    ]
}