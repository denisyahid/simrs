# Load Test Script For Jasamedika SIMRS

Load-test scripts for jasamedika SIMRS using [k6](https://k6.io/).

## Run

All script file are inside [test](./test/) directory. To run execute the following command.

```bash
k6 run <path/to/javacript/file> --vus <number of conncurent threat> --duration <duration>
```

Example of running save-pasien-fix test

```bash
k6 run test/registrasi/passien/save-pasien-fix.js --vus 20 --duration 1m
```

### Using Docker

```bash
docker run \
    -it \
    --rm \
    -v $PWD:$PWD \
    -w $PWD \
    --net host \
    --user 0 \
    -e TEST_BASE_URL=http://localhost:8100 \
    grafana/k6 \
        run ./test/index.js
```

### Debug

To debug, run one interation with `--http-debug=full` option. Example:

```bash
k6 run test/registrasi/passien/save-pasien-fix.js --http-debug=full
```
### Debug with url

To debug, run one interation with `--http-debug=full` option. Example:

```bash
TEST_BASE_URL=http://localhost:8100  k6 run test/registrasi/passien/save-pasien-fix.js --vus 20 --duration 1m --http-debug=full 
```