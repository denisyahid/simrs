#!/usr/bin/env node
require("amd-loader");

const config = require("./setting");
const HashMap = require("hashmap");

const http = require("http");
const bodyParser = require("body-parser");

const RabbitHole = require("./rabbitHole");
const Storage = require("node-storage");

console.log("RabbitMQ Server Host : %s", config.rabbitMQHost);


const { App } = require("uWebSockets.js");
const { Server } = require("socket.io");


const app = new App();
const io = new Server({
  credentials: true,
  cors: {
    origin: "*",
    methods: ["GET", "POST", "PUT", "DELETE"],
    credentials: true,
  },
  allowEIO3: true,
});

io.attachApp(app);

var storage = new Storage("./notif.dat");
var desktop = new Storage("./desktop.dat");

var kumpulanSocketByKdRuangan = new HashMap();
var rabbitConnByKdRuangan = new HashMap();
var notifJSONByKdRuangan = new HashMap();

var kdRuanganBySocketId = new HashMap();

var kumpulanSocketByKdJabatan = new HashMap();
var rabbitConnByKdJabatan = new HashMap();
var notifJSONByKdJabatan = new HashMap();

var kdJabatanBySocketId = new HashMap();

io.on("connection", function (socket) {
  console.log(socket.conn.remoteAddress + " connected");

  // socket.on('notif.desktop', notifBroadCast);

  socket.on("klinik.register.server", function (data) {
    console.log(
      "dari server - klinik.register.server : " + JSON.stringify(data)
    );
    io.emit("klinik.register.client", data);
  });

  socket.on("klinik.register.server.balasan", function (data) {
    console.log(
      "dari client - klinik.register.server.balasan : " + JSON.stringify(data)
    );
    io.emit("klinik.register.client.balasan", data);
  });

  socket.on(
    "klinik.monitoring.antrian.registrasi.batal.server",
    function (data) {
      console.log(
        "dari client - klinik.monitoring.antrian.registrasi.batal.server : " +
          JSON.stringify(data)
      );
      io.emit("klinik.monitoring.antrian.registrasi.batal.client", data);
    }
  );

  socket.on("performance.progress.bar", function (data) {
    io.emit("performance.progress.bar.frontend", data);
  });

  // Notifikasi Lama

  socket.on("broadcast", function (data) {
    console.log(data);
  });

  socket.on("subscribe", function (data) {
    console.log("ini dia");
    console.log(data);
    try {
      if (data.to === undefined) {
        var arr = data.split("#");
        if (arr.length == 3) {
          console.log("send to " + arr[0] + " >> " + arr[1] + "#" + arr[2]);
          socket.broadcast.emit(arr[0], {
            message: arr[1] + "#" + arr[2],
          });
        } else if (arr.length == 2) {
          console.log("send to " + arr[0] + " >> " + arr[1]);
          socket.broadcast.emit(arr[0], {
            message: arr[1],
          });
        } else console.log("kemana yeeeh");
      } else {
        socket.broadcast.emit(data.to, {
          message: data.message,
        });
      }
    } catch (e) {}
  });
  // Notifikasi Baru
  // using localstorage

  socket.on("disconnect", function () {
    clearRuangan();
    clearJabatan();
  });

  socket.on("login", function (data) {
    try {
      var peg = JSON.parse(data);
      console.log("User %s berhasil login", peg.namaLengkap);
      socket.broadcast.emit("login", peg);
      // socket.broadcast.emit('loginsaha', peg);
    } catch (err) {}
  });

  socket.on("logout", function (data) {
    try {
      var peg = JSON.parse(data);
      console.log("User %s telah logout", peg.namaLengkap);
      socket.broadcast.emit("logout", peg);
    } catch (err) {}
    socket.disconnect();
  });

  socket.on("deleteNotif", function (data) {
    var jsonNotif = JSON.parse(data);

    var cKdRuangan = jsonNotif.kdRuangan;
    var notif = jsonNotif.notif;

    console.log(
      "Notification ruangan yang akan dibuang %s ",
      JSON.stringify(notif)
    );

    var notifMsg = notifJSONByKdRuangan.get(cKdRuangan);
    console.log("ruangan", JSON.stringify(notifMsg));

    if (notifMsg == undefined || notifMsg == null || notifMsg.length <= 0) {
      notifMsg = storage.get("kdRuangan." + cKdRuangan);
    }

    if (!(notifMsg == undefined || notifMsg == null || notifMsg.length <= 0)) {
      notifJSONByKdRuangan.set(cKdRuangan, notifMsg);
      var idx = notifMsg.indexOf(notif);
      console.log("Notification idx yang akan dihapus %s ", idx);
      notifMsg.splice(idx, 1);
      console.log("Notification idx yang dihapus %s ", idx);
      storage.put("kdRuangan." + cKdRuangan, notifMsg);
      io.emit("listNotif.ruangan." + cKdRuangan, JSON.stringify(notifMsg));
    }

    /////////////////////////////////////////////////////////////

    var cKdJabatan = jsonNotif.kdJabatan;
    var notif = jsonNotif.notif;

    console.log(
      "Notification jabatan yang akan dibuang %s ",
      JSON.stringify(notif)
    );

    var notifMsg = notifJSONByKdJabatan.get(cKdJabatan);
    console.log("jabatan", JSON.stringify(notifMsg));

    if (notifMsg == undefined || notifMsg == null || notifMsg.length <= 0) {
      notifMsg = storage.get("kdJabatan." + cKdJabatan);
    }

    if (!(notifMsg == undefined || notifMsg == null || notifMsg.length <= 0)) {
      notifJSONByKdJabatan.set(cKdJabatan, notifMsg);
      var idx = notifMsg.indexOf(notif);
      console.log("Notification idx yang akan dihapus %s ", idx);
      notifMsg.splice(idx, 1);
      console.log("Notification idx yang dihapus %s ", idx);
      storage.put("kdJabatan." + cKdJabatan, notifMsg);
      io.emit("listNotif.jabatan." + cKdJabatan, JSON.stringify(notifMsg));
    }
  });

  //////////////////// RUANGAN /////////////////////////////////

  var clearRuangan = function () {
    console.log("socket dan ruangan dibersihkan");

    kdRuanganBySocketId.set(socket.id, cKdRuangan);
    var cKdRuangan = kdRuanganBySocketId.get(socket.id);

    var totalSocket = kumpulanSocketByKdRuangan.get(cKdRuangan);
    var rabbit = {};

    if (totalSocket == undefined || totalSocket == null) {
      rabbit = rabbitConnByKdRuangan.get(cKdRuangan);
      if (rabbit == undefined || rabbit == null) {
        return;
      }
      rabbit.disconnect();
      rabbitConnByKdRuangan.set(cKdRuangan, null);
      return;
    }

    var idx = totalSocket.indexOf(socket.id);
    totalSocket.splice(idx, 1);

    if (totalSocket.length <= 0) {
      kumpulanSocketByKdRuangan.set(cKdRuangan, null);
      rabbit = rabbitConnByKdRuangan.get(cKdRuangan);
      if (rabbit == undefined || rabbit == null) {
        return;
      }
      rabbit.disconnect();
      rabbitConnByKdRuangan.set(cKdRuangan, null);
    }
  };

  //////////////////// JABATAN /////////////////////////////////

  var clearJabatan = function () {
    console.log("socket dan jabatan dibersihkan");

    kdJabatanBySocketId.set(socket.id, cKdJabatan);
    var cKdJabatan = kdJabatanBySocketId.get(socket.id);

    var totalSocket = kumpulanSocketByKdJabatan.get(cKdJabatan);
    var rabbit = {};

    if (totalSocket == undefined || totalSocket == null) {
      rabbit = rabbitConnByKdJabatan.get(cKdJabatan);
      if (rabbit == undefined || rabbit == null) {
        return;
      }
      rabbit.disconnect();
      rabbitConnByKdJabatan.set(cKdJabatan, null);
      return;
    }

    var idx = totalSocket.indexOf(socket.id);
    totalSocket.splice(idx, 1);

    if (totalSocket.length <= 0) {
      kumpulanSocketByKdJabatan.set(cKdJabatan, null);
      rabbit = rabbitConnByKdJabatan.get(cKdJabatan);
      if (rabbit == undefined || rabbit == null) {
        return;
      }
      rabbit.disconnect();
      rabbitConnByKdJabatan.set(cKdJabatan, null);
    }
  };

  //////////////////// RUANGAN /////////////////////////////////

  socket.on("kdRuangan", function (data) {
    var cKdRuangan = data;

    console.log(" [*] ada user dari  ruangan : %s ...", cKdRuangan);

    kdRuanganBySocketId.set(socket.id, cKdRuangan);

    var notifMsg = notifJSONByKdRuangan.get(cKdRuangan);

    if (notifMsg == undefined || notifMsg == null || notifMsg.length <= 0) {
      notifMsg = storage.get("kdRuangan." + cKdRuangan);
    }

    if (notifMsg == undefined || notifMsg == null || notifMsg.length <= 0) {
      notifMsg = [];
      storage.put("kdRuangan." + cKdRuangan, notifMsg);
    }

    notifJSONByKdRuangan.set(cKdRuangan, notifMsg);

    var totalSocket = kumpulanSocketByKdRuangan.get(cKdRuangan);

    if (
      totalSocket == undefined ||
      totalSocket == null ||
      totalSocket.length <= 0
    ) {
      totalSocket = [];
      kumpulanSocketByKdRuangan.set(cKdRuangan, totalSocket);
    }

    if (notifMsg.length > 0) {
      console.log(
        "kirimkan notif yang sudah ada donk.." +
          "listNotif.ruangan." +
          cKdRuangan
      );
      socket.emit("listNotif.ruangan." + cKdRuangan, JSON.stringify(notifMsg));
      //for (let i=0; i<notifMsg.length; i++){
      //    sendAllDesktopNotif(JSON.stringify(notifMsg[i]));
      //}
    }

    totalSocket.push(socket.id);
    var rabbit = rabbitConnByKdRuangan.get(cKdRuangan);

    if (rabbit == undefined || rabbit == null) {
      rabbit = new RabbitHole();
      rabbitConnByKdRuangan.set(cKdRuangan, rabbit);

      rabbit.connect(config.rabbitMQHost, function (conn) {
        conn
          .createChannel()
          .then(function (ch) {
            try {
              console.log(
                " [*] Menunggu pesan dari Queue : %s ...",
                cKdRuangan
              );

              var callbackConsume = function (msg) {
                //console.log(" [x] Ruangan : %s Menerima pesan %s", cKdRuangan, msg.content.toString());

                var totalSocket = kumpulanSocketByKdRuangan.get(cKdRuangan);

                if (
                  totalSocket == undefined ||
                  totalSocket == null ||
                  totalSocket.length <= 0
                ) {
                  ch.nack(msg, false, true);
                  rabbit.disconnect();
                  rabbitConnByKdRuangan.set(cKdRuangan, null);
                } else {
                  var notif = msg.content.toString();
                  ch.ack(msg);
                  // sendAllDesktopNotif(notif);

                  var notifMsgLocal = notifJSONByKdRuangan.get(cKdRuangan);

                  console.log(
                    "simpan notif ke penyimpanan %s",
                    "kdRuangan." + cKdRuangan
                  );

                  notifMsgLocal.push(JSON.parse(notif));

                  storage.put("kdRuangan." + cKdRuangan, notifMsgLocal);

                  //                                console.log('kirim pesan ke ruangan %d isinya %s', cKdRuangan, JSON.stringify(notifMsg));

                  io.emit(
                    "listNotif.ruangan." + cKdRuangan,
                    JSON.stringify(notifMsgLocal)
                  );
                }
              };

              var common_options = { durable: true, noAck: false };

              console.log("consume ruangan %s", cKdRuangan);

              ch.assertQueue(cKdRuangan, common_options).then();
              ch.consume(cKdRuangan, callbackConsume, common_options);
            } catch (err) {
              console.error(
                "Ada error saat baca Channel, abaikan. Errornya : %s",
                err
              );
            }
          })
          .then(null, function (err) {
            console.error("Gagal bikin channel karena %s ", err);
          });
      });
    }
  });

  //////////////////// JABATAN /////////////////////////////////

  socket.on("kdJabatan", function (data) {
    var cKdJabatan = data;

    console.log(" [*] ada user dengan jabatan : %s ...", cKdJabatan);

    kdJabatanBySocketId.set(socket.id, cKdJabatan);

    var notifMsg = notifJSONByKdJabatan.get(cKdJabatan);

    if (notifMsg == undefined || notifMsg == null || notifMsg.length <= 0) {
      notifMsg = storage.get("kdJabatan." + cKdJabatan);
    }

    if (notifMsg == undefined || notifMsg == null || notifMsg.length <= 0) {
      notifMsg = [];
      storage.put("kdJabatan." + cKdJabatan, notifMsg);
    }

    notifJSONByKdJabatan.set(cKdJabatan, notifMsg);

    var totalSocket = kumpulanSocketByKdJabatan.get(cKdJabatan);

    if (
      totalSocket == undefined ||
      totalSocket == null ||
      totalSocket.length <= 0
    ) {
      totalSocket = [];
      kumpulanSocketByKdJabatan.set(cKdJabatan, totalSocket);
    }

    if (notifMsg.length > 0) {
      console.log(
        "kirimkan notif yang sudah ada donk.. " +
          "listNotif.jabatan." +
          cKdJabatan
      );
      socket.emit("listNotif.jabatan." + cKdJabatan, JSON.stringify(notifMsg));
    }

    totalSocket.push(socket.id);
    var rabbit = rabbitConnByKdJabatan.get(cKdJabatan);

    if (rabbit == undefined || rabbit == null) {
      rabbit = new RabbitHole();
      rabbitConnByKdJabatan.set(cKdJabatan, rabbit);

      rabbit.connect(config.rabbitMQHost, function (conn) {
        conn
          .createChannel()
          .then(function (ch) {
            try {
              console.log(
                " [*] Menunggu pesan dari Queue : %s ...",
                cKdJabatan
              );

              var callbackConsume = function (msg) {
                //console.log(" [x] Ruangan : %s Menerima pesan %s", cKdJabatan, msg.content.toString());

                var totalSocket = kumpulanSocketByKdJabatan.get(cKdJabatan);

                if (
                  totalSocket == undefined ||
                  totalSocket == null ||
                  totalSocket.length <= 0
                ) {
                  ch.nack(msg, false, true);
                  rabbit.disconnect();
                  rabbitConnByKdJabatan.set(cKdJabatan, null);
                } else {
                  var notif = msg.content.toString();
                  ch.ack(msg);
                  sendAllDesktopNotif(notif);

                  var notifMsgLocal = notifJSONByKdJabatan.get(cKdJabatan);

                  console.log(
                    "simpan notif ke penyimpanan %s",
                    "kdJabatan." + cKdJabatan
                  );

                  //                               var dataNotif = JSON.parse(notif);

                  // if ((dataNotif.data === undefined || dataNotif.data === null) || (dataNotif.tipe !== 1)) {
                  //     return;
                  // }

                  notifMsgLocal.push(JSON.parse(notif));
                  storage.put("kdJabatan." + cKdJabatan, notifMsgLocal);

                  console.log(
                    "kirim pesan ke %s isinya %s",
                    "listNotif.jabatan." + cKdJabatan,
                    JSON.stringify(notifMsg)
                  );

                  io.emit(
                    "listNotif.jabatan." + cKdJabatan,
                    JSON.stringify(notifMsgLocal)
                  );
                }
              };

              var common_options = { durable: true, noAck: false };

              console.log("consume jabatan %s", cKdJabatan);

              ch.assertQueue(cKdJabatan, common_options).then();
              ch.consume(cKdJabatan, callbackConsume, common_options);
            } catch (err) {
              console.error(
                "Ada error saat baca Channel, abaikan. Errornya : %s",
                err
              );
            }
          })
          .then(null, function (err) {
            console.error("Gagal bikin channel karena %s ", err);
          });
      });
    }
  });
  socket.on("caller", function (data) {
    var dataCaller = data;

    console.log(" [*] ada caller dengan data : %s ...", dataCaller);

    io.emit("tampilkan", JSON.stringify(dataCaller));
  });
  socket.on("load-caller", function (data) {
    var dataCaller = data;

    console.log(" [*] load list no antrian ");

    io.emit("get-list-antrian", JSON.stringify(dataCaller));
  });
  socket.on("call-antrian-poli", function (data) {
    var dataCaller = data;

    console.log(" [*] ada panggilan poli dengan data : %s ...", dataCaller);
    var norec = data.norec ? data.norec : "";
    // saveLog('Panggil Antrian Poli','','','Antrian Poli Panggil : ' +new Date().toLocaleString() +' ' + data.namapasien + ' ' + data.namaruangan + '('+norec+')' );
    io.emit("tampilkan-antrian-poli", JSON.stringify(dataCaller));
  });
  socket.on("refresh-form-dokter-perawat", function () {
    console.log(" [*] ada pasien yang daftar poli dengan data ");
    io.emit("gas-refresh-form-dokter-perawat");
  });

  socket.on("call-perawat", function (data) {
    var dataCaller = data;
    console.log(
      " [*] ada panggilan ke perawat dengan data : %s ...",
      dataCaller
    );
    io.emit("suara-perawat", JSON.stringify(dataCaller));
  });
  socket.on("get-server-socket", function (data) {
    var dataSocket = data;
    console.log(" [*] ada socket server euy : %s ...", JSON.stringify(dataSocket));
    io.emit("set-server-socket", JSON.stringify(dataSocket));
  });
  socket.on("call-antrian-farmasi", function (data) {
    var dataCaller = data;

    console.log(" [*] ada panggilan farmasi dengan data : %s ...", dataCaller);

    io.emit("tampilkan-antrian-farmasi", JSON.stringify(dataCaller));
  });
});

let portListen = config.portSocket;

app.listen(portListen, (token) => {
  if (!token) {
    console.warn("port already in use");
    return;
  }

  console.log("uWebsocket.js listening on port " + portListen);
});
