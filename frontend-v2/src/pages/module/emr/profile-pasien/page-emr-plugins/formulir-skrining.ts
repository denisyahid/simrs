export function anamnesa():any{
  return [
    {
      "no" : "1",
      "pertanyaan" : "Demam",
      "model" : "isDemam",
      "value" : {
        "ket" : "Ya",
        "poin" : 1
      },
      "skor" : 1
    },
    {
      "no" : "2",
      "pertanyaan" : "Batuk/Pilek/Nyeri Tenggorokan",
      "model" : "isNyeri",
      "value" : {
        "ket" : "Ya",
        "poin" : 1
      },
      "skor" : 1
    },
    {
      "no" : "3",
      "pertanyaan" : "Kontak erat/Kasus Konfirmasi",
      "model" : "kasusKonfirmasi",
      "value" : {
        "ket" : "Ya",
        "poin" : 1
      },
      "skor" : 1
    },
    {
      "no" : "4",
      "pertanyaan" : "Riwayat pemeriksaan Swab test (PCR)",
      "model" : "riwayatPCR",
      "value" : {
        "ket" : "Ya",
        "poin" : 1
      },
      "skor" : 1
    },
  ]
}
export function gejalaKlinis():any{
  return [
    {
      "no" : "1",
      "pertanyaan" : "Suhu 38°C",
      "model" : "suhu",
      "value" : {
        "ket" : "Ya",
        "poin" : 1
      },
      "skor" : 1
    },
    {
      "no" : "2",
      "pertanyaan" : "Sesak Nafas",
      "model" : "pernafasan",
      "value" : {
        "ket" : "Ya",
        "poin" : 1
      },
      "skor" : 1
    },
  ]
}
