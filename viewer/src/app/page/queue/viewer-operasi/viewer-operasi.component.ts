import { Component, OnInit, OnDestroy } from '@angular/core';
import { ApiService } from 'src/app/service';
import { SocketService } from 'src/app/service/socket.service';
import { ActivatedRoute } from '@angular/router';
import { Config } from 'src/app/guard';
import * as XLSX from 'xlsx';

@Component({
  selector: 'app-operasi',
  templateUrl: './viewer-operasi.component.html',
  styleUrls: ['./viewer-operasi.component.scss']
})
export class ViewerOperasiComponent implements OnInit, OnDestroy {
  dateNow: any = new Date();
  jamSekarang: any;
  isLogin: boolean = true;
  tgl: any;
  apiTimer: any;
  refreshTimer: any; // Timer for refreshing data
  namaProfile = Config.getProfile().namaProfile;
  listberjalan: any[] = [];
  listmenunggu: any[] = [];
  listrecoveryroom: any[] = [];
  columnMenunggu: any[] = [];
  columnRecoveryRoom: any[] = [];
  color: any[] = ['bg-gradient-danger', 'bg-gradient-info', 'bg-gradient-success', 'bg-gradient-primary'];

  constructor(
    private socket: SocketService,
    private apiService: ApiService,
    private route: ActivatedRoute,
  ) {
    this.socket.on("tampilkan-operasi", (data: any) => {
      this.loadAwal();
    });
    
    // Timer to fetch the current time
    this.apiTimer = setInterval(() => {
      this.getdate();
    }, 1000);

    // Timer to refresh data every 5 seconds (adjust as needed)
    this.refreshTimer = setInterval(() => {
      this.loadAwal();
    }, 15000); // Fetch new data every 5000 milliseconds (5 seconds)
  }

  ngOnInit(): void {
    const showStatusOperasi = false;
    this.columnMenunggu = [
      { field: 'jammulaioperasi', header: 'Waktu Operasi', width: "150px" },
      { field: 'nocm', header: 'No RM', width: "50px"},
      { field: 'jeniskelamin', header: 'Jenis Kelamin', width: "100px" },
      { field: 'umur', header: 'Umur', width: "200px" },
      { field: 'namaproduk', header: 'Tindakan', width: "100px" },
      { field: 'diagnosis', header: 'Diagnosa', width: "100px" },
      { field: 'kelompokpasien', header: 'Tipe Pasien', width: "150px" },
      { field: 'dokterop', header: 'Dokter Operator', width: "300px" },
      { field: 'dokteranis', header: 'Dokter Anestesi', width: "270px" },
      { field: 'dokteranak', header: 'Dokter Anak', width: "270px" },
      ...(showStatusOperasi ? [{ field: 'statusoperasi', header: 'Status', width: "150px" }] : []),
      { field: 'estimasiwaktuoperasi', header: 'Estimasi Waktu (menit)', width: "150px" },
      { field: 'namakamarok', header: 'Ruangan OK', width: "150px" },
    ];

    this.columnRecoveryRoom = [
      { field: 'nocm', header: 'No RM', width: "100px" },
      { field: 'namapasien', header: 'Nama Pasien', width: "150px" },
    ];

    this.loadAwal();
  }

  ngOnDestroy(): void {
    // Clear intervals to avoid memory leaks
    clearInterval(this.apiTimer);
    clearInterval(this.refreshTimer);
  }

  loadAwal() {
    this.apiService.get('viewer/get-data-viewer-ok').subscribe(e => {
      this.listberjalan = e.berjalan;
      this.listmenunggu = e.menunggu.map((item: any) => {
        return { ...item, umur: this.calculateAge(item.tgllahir) };
      });
      this.listrecoveryroom = e.recoveryroom;
    });
  }

  getdate() {
    const today = new Date();
    const h: any = today.getHours().toString().padStart(2, '0');
    const m: any = today.getMinutes().toString().padStart(2, '0');
    const s: any = today.getSeconds().toString().padStart(2, '0');

    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const day = today.getDate();
    const month = today.getMonth();
    const thisDay = myDays[today.getDay()];
    const year = today.getFullYear();

    this.tgl = `${thisDay}, ${day} ${months[month]} ${year}`;
    this.jamSekarang = `${h}:${m}:${s} WIB`;
  }

  calculateAge(tgllahir: string): string {
    if (!tgllahir) return '-';
  
    const birthDate = new Date(tgllahir);
    const today = new Date();
  
    if (isNaN(birthDate.getTime())) return '-';
  
    let years = today.getFullYear() - birthDate.getFullYear();
    let months = today.getMonth() - birthDate.getMonth();
    let days = today.getDate() - birthDate.getDate();
  
    if (days < 0) {
      months--;
      days += new Date(today.getFullYear(), today.getMonth(), 0).getDate();
    }
    if (months < 0) {
      years--;
      months += 12;
    }
  
    return `${years} Thn ${months} Bln ${days} Hr`;
  }

  getStatusClass(status: number): string {
    return 'status-' + status;
  }
  exportToExcel() {
    const data = this.listmenunggu.map(rowData =>
      this.columnMenunggu.reduce((acc, column) => {
        acc[column.header] = rowData[column.field];
        return acc;
      }, {} as Record<string, any>)
    );

    // Create a new workbook and worksheet
    const worksheet = XLSX.utils.json_to_sheet(data);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Jadwal Operasi');

    // Export to Excel file
    XLSX.writeFile(workbook, 'JadwalOperasi.xlsx');
  }
}
