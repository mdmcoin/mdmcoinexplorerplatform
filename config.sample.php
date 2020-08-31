<?php

if( PHP_INT_SIZE < 8 )
    exit( 'ERROR: 64-bit required' );

define( 'W8IO_DB_DIR', './var/db/' );
define( 'W8IO_NODES', 'https://nodes.wavesnodes.com' );
define( 'W8IO_NETWORK', 'T' ); // 'W' -- mainnet, 'T' -- testnet
define( 'W8IO_ROOT', '/' );

define( 'W8IO_HEIGHT_CORRECTION', 1 );
define( 'W8IO_CACHE_PAIRS', 1024 );
define( 'W8IO_CACHE_BLOCKS', 32 );
define( 'W8IO_DB_PRAGMAS', 'PRAGMA temp_store = MEMORY;' );
define( 'W8IO_DB_WRITE_PRAGMAS', 'PRAGMA synchronous = NORMAL; PRAGMA journal_mode = WAL; PRAGMA journal_size_limit = 16777216; PRAGMA optimize;' );
define( 'W8IO_DB_BLOCKCHAIN', W8IO_DB_DIR . 'sqlite:blockchain.sqlite3' );
define( 'W8IO_DB_BLOCKCHAIN_TRANSACTIONS', W8IO_DB_DIR . 'blockchain_transactions.sqlite3' );
define( 'W8IO_DB_BLOCKCHAIN_BALANCES', W8IO_DB_DIR . 'blockchain_balances.sqlite3' );
define( 'W8IO_DB_BLOCKCHAIN_AGGREGATE', W8IO_DB_DIR . 'blockchain_aggregate.sqlite3' );
define( 'W8IO_CHECKPOINT_BLOCKCHAIN', 0 );
define( 'W8IO_CHECKPOINT_BLOCKCHAIN_TRANSACTIONS', 1 );
define( 'W8IO_CHECKPOINT_BLOCKCHAIN_BALANCES', 2 );
define( 'W8IO_CHECKPOINT_BLOCKCHAIN_AGGREGATE', 3 );
define( 'W8IO_MAX_UPDATE_BATCH', 1 );
define( 'W8IO_UPDATE_DELAY', 10 );