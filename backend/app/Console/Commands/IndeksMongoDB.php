<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class IndeksMongoDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mongo:indexing {--collection=} {--fields=} {--compound} {--drop-old} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = <<<EOD
    Indexing Collection(s) mongoDB

    Contoh penggunaan:
    php artisan mongo:indexing --collection=users
    php artisan mongo:indexing --collection=users --fields=email:asc,id:desc,nama
    php artisan mongo:indexing --collection=users --fields=email:asc,id:desc,nama --drop-old
    php artisan mongo:indexing --collection=users --fields=email,nama --force
    php artisan mongo:indexing --collection=RingkasanKeluar --fields=registrasi.noregistrasi:asc,statusenabled:asc,profile.kdprofile:asc --compound

    Keterangan:
    --fields : Daftar nama field yang ingin diindeks.
                Format: nama_field[:asc|desc]
                Default index jika tidak gunakan: asc (1)

    --force  : Skip pengecekan apakah field tersebut sudah punya index
    --drop-old : Hapus index lama yang tidak ada di option --fields (kecuali _id) WAJIB ADA OPTION --fields
    --compound : untuk membuat index gabungan seperti registrasi.noregistrasi, dll.
    EOD;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $collection = $this->option('collection');
        $force = $this->option('force');
        $dropOld = $this->option('drop-old');
        $fields = $this->option('fields');
        $collections = DB::connection('mongodb')->getMongoDB()->listCollections();

        $arr = [];
        foreach ($collections as $col) {
            $arr[] = $col->getName();
        }
        if($collection && !in_array($collection, $arr)) {
            $this->error("Collection $collection tidak ditemukan!");
            return;
        }

        $arr = $collection ? [$collection] : $arr;
        $indexField = [];

        if ($dropOld && !$fields) {
            $this->error("--drop-old membutuhkan --fields untuk menentukan index mana yang digunakan dan dibuang");
            return;
        }


        if ($fields) {
             if ($this->option('compound')) {
                $this->info("ccompound index: $collection");
                $compoundIndexKeys = [];
                $mapFields = array_map('trim', explode(',', $fields));
                foreach ($mapFields as $fieldDef) {
                    [$field, $orderIndex] = array_pad(explode(':', $fieldDef), 2, 'asc');
                    $orderValue = (strtolower($orderIndex) === 'desc') ? -1 : 1;
                    $compoundIndexKeys[$field] = $orderValue;
                }
                
                try {
                    DB::connection('mongodb')->getMongoDB()->selectCollection($collection)->createIndex($compoundIndexKeys, ['background' => true]);
                    $this->info("compound" . json_encode($compoundIndexKeys) . " berhasil dibuat.");
                } catch (\Exception $e) {
                    $this->error("Gagal membuat index gabungan: " . $e->getMessage());
                }

            } else {
                $mapFields = array_map('trim', explode(',', $fields));
                foreach ($mapFields as $fieldDef) {
                    if (str_contains($fieldDef, ':')) {
                        // $field = explode(':', $fieldDef)[0];
                        // $orderIndex = explode(':', $fieldDef)[1];
                        // $this->info("direc $field");
                        // return;

                        [$field, $orderIndex] = explode(':', $fieldDef);
                        $orderIndex = strtolower($orderIndex);
                        if ($orderIndex === 'desc') {
                            $indexField[] = [$field => -1];
                        } elseif ($orderIndex === 'asc') {
                            $indexField[] = [$field => 1];
                        } else {
                            $this->warn("Order index '$orderIndex' tidak valid untuk field '$field', gunakan 'asc' atau 'desc'. default: asc");
                            $indexField[] = [$field => 1];
                        }
                    } else {
                        $indexField[] = [$fieldDef => 1];
                    }
                }
            }
        }else {
            $indexField = [
                ['pasien.nocmfk' => 1],
                ['profile.kdprofile' => 1],
                ['statusenabled' => 1],
                ['registrasi.norec_pd' => 1],
                ['emrpasienfk' => 1],
                ['noorder' => 1],
                ['registrasi.namaruangan' => 1],
                ['userBy' => 1],
                ['index_tabs' => 1],
                ['created_at' => -1]
            ];
        }

        $indexasli = [];
        foreach ($indexField as $fieldDef) {
            foreach ($fieldDef as $key => $order) {
                $indexasli[$key] = $order;
            }
        }

        // $this->info(json_encode($indexField));
        // return;

        foreach ($arr as $db) {
            try {
                $collectionObj = DB::connection('mongodb')->getMongoDB()->selectCollection($db);
                $existingIndexes = $collectionObj->listIndexes();
        
                $existingIndexKeys = [];
                foreach ($existingIndexes as $index) {
                    $keys = $index->getKey();
                    foreach ($keys as $key => $val) {
                        $existingIndexKeys[$key] = $val;
                    }
                }

                // if()
                // $indexField = [
                //     ['pasien.nocmfk' => 1],
                //     ['profile.kdprofile' => 1],
                //     ['statusenabled' => 1],
                //     ['registrasi.norec_pd' => 1],
                //     ['emrpasienfk' => 1],
                //     ['noorder' => 1],
                //     ['registrasi.namaruangan' => 1],
                //     ['userBy' => 1],
                //     ['index_tabs' => 1],
                //     ['created_at' => -1]
                // ];
        
                foreach ($indexField as $index) {
                    $indexKey = array_keys($index)[0];
                    $cekFields = $collectionObj->findOne([$indexKey => ['$exists' => true]]);
                    if (!$cekFields && !$force) {
                        $this->warn("Field '$indexKey' tidak ditemukan di collection '$db'. Index tidak dibuat.");
                        continue;
                    }

                    if (!isset($existingIndexKeys[$indexKey])) {
                        $collectionObj->createIndex($index);
                        $this->info("Indeks berhasil dibuat: " . json_encode($index) . " di collection: $db");
                    } else {
                        $this->info("Indeks sudah ada: " . json_encode($index) . " di collection: $db");
                    }
                }

                if ($dropOld) {
                    foreach ($existingIndexes as $index) {
                        $keys = $index->getKey();
                        // $this->info("KEYNYA: $keys");
                        // return;
                        if (isset($keys['_id'])) {
                            continue;
                        }

                        $hapus = true;
                        foreach ($keys as $key => $val) {
                            if (isset($indexasli[$key]) && $indexasli[$key] === $val) {
                                $hapus = false;
                                break;
                            }
                        }

                        if ($hapus) {
                            // $this->info("msk hapus: $index")
                            // return;
                            $indexName = $index->getName();
                            try {
                                $collectionObj->dropIndex($indexName);
                                $this->warn("Index '$indexName' dihapus dari collection '$db'");
                            } catch (\Exception $e) {
                                $this->error("Gagal menghapus index '$indexName' di collection '$db': " . $e->getMessage());
                            }
                        }
                    }
                }

            } catch (\Exception $e) {
                $this->error("Terjadi Kesalahan pada collection : $db");
                $this->error($e->getMessage());
            }
        }
    }
}
