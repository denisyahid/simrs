define([], function () {
  "use strict";
  return {
    rabbitMQHost: "amqp://rsab:rsab@127.0.0.1",
    portSocket: 2530,
    mode: "http",
    mssql: {
      user: "",
      server: "",
      port: 1435,
      database: "",
    },
    pgsql: {
      user: "",
      password: "",
      server: "",
      port: 5432,
      database: "",
    },
  };
});
