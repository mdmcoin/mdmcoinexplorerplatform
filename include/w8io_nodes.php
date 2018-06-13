<?php

require_once 'w8io_base.php';

class w8io_nodes
{
    private $cdb = array();

    public function __construct( $hosts, $api = '' )
    {
        foreach( $hosts as $host )
            $this->cdb[] = array( 'host' => $host, 'curl' => false );
    }

    private function connect( $host )
    {
        if( false === ( $ch = curl_init() ) )
            w8io_error( 'curl_init() failed' );
            
        if( false === curl_setopt_array( $ch, array (
            CURLOPT_CONNECTTIMEOUT  => 1,
            CURLOPT_TIMEOUT         => 5,
            CURLOPT_URL             => $host,
            CURLOPT_CONNECT_ONLY    => true,
            CURLOPT_CAINFO          => './third_party/ca-bundle/res/cacert.pem',
            //CURLOPT_SSL_VERIFYPEER  => false, // not secure
        ) ) )
            w8io_error( 'curl_setopt_array() failed' );

        $ms = 0;
        w8io_timer( $ms );
        {
            curl_exec( $ch );
        }
        $ms = w8io_ms( w8io_timer( $ms ) );

        if( 0 != ( $errno = curl_errno( $ch ) ) )
        {
            w8io_trace( 'w', "$host error $errno: " . curl_error( $ch ) );
            curl_close( $ch );
            return false;
        }

        if( false === curl_setopt_array( $ch, array (
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_CONNECT_ONLY    => false,
        ) ) )
            w8io_error( 'curl_setopt_array() failed' );

        w8io_trace( 'i', "$host connected ($ms ms)" );
        return $ch;
    }

    private function connector()
    {
        static $last_c = 0;
        static $last_time = 0;
        $time = time();

        if( $last_c && $last_time && $time - $last_time > 1 )
        {
            w8io_trace( 'i', 'refresh connector' );
            $last_c = 0;
        }

        $n = sizeof( $this->cdb );
        for( ;; )
        {
            for( $i = $last_c; $i < $n; $i++ )
            {
                $c = &$this->cdb[$i];
                if( is_resource( $c['curl'] ) )
                {
                    $last_c = $i;
                    $last_time = $time;
                    return $c;
                }

                $ch = self::connect( $c['host'] );
                if( $ch )
                {
                    $c['curl'] = $ch;
                    $last_c = $i;
                    $last_time = $time;
                    return $c;
                }
            }

            w8io_trace( 'w', 'no connection...' );
            $last_c = 0;
            sleep( 1 );
        }
    }

    public function get( $url, $trace = false, $api = false )
    {
        $c = $this->connector();
        $host = $c['host'];
        $ch = $c['curl'];

        if( false === curl_setopt_array( $ch, array (
            CURLOPT_HTTPHEADER      => array( 'Content-Type: application/json', 'Accept: application/json', $api ? "X-API-Key: $api" : '', ),
            CURLOPT_URL             => $host . $url,
            CURLOPT_POST            => false,
        ) ) )
            w8io_error( 'curl_setopt_array() failed' );

        $ms = 0;
        w8io_timer( $ms );
        {
            $data = curl_exec( $ch );
        }
        $ms = w8io_ms( w8io_timer( $ms ) );

        if( 0 != ( $errno = curl_errno( $ch ) ) )
        {
            w8io_trace( 'w', "$host error $errno: " . curl_error( $ch ) );
            curl_close( $ch );
            return false;
        }

        if( $trace )
            w8io_trace( 'i', "$host GET $url ($ms ms)" );
        
        return $data;
    }

    public function post( $url, $data, $api )
    {
        $c = $this->connector();
        $host = $c['host'];
        $ch = $c['curl'];

        if( false === curl_setopt_array( $ch, array (
            CURLOPT_HTTPHEADER      => array( 'Content-Type: application/json', 'Accept: application/json', $api ? "X-API-Key: $api" : '', ),
            CURLOPT_URL             => $host . $url,
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS      => $data,
        ) ) )
            w8io_error( 'curl_setopt_array() failed' );

        $ms = 0;
        w8io_timer( $ms );
        {
            $data = curl_exec( $ch );
        }
        $ms = w8io_ms( w8io_timer( $ms ) );

        if( 0 != ( $errno = curl_errno( $ch ) ) )
        {
            w8io_trace( 'w', "$host error $errno: " . curl_error( $ch ) );
            curl_close( $ch );
            return false;
        }

        w8io_trace( 'i', "$host POST $url ($ms ms)" );

        return $data;
    }

    public function get_height()
    {
        $json = json_decode( self::get( '/blocks/height', true ), true, 512, JSON_BIGINT_AS_STRING );

        if( !isset( $json['height'] ) )
            return false;

        return $json['height'];
    }

    public function get_block( $at )
    {
        $json = json_decode( self::get( "/blocks/at/$at", true ), true, 512, JSON_BIGINT_AS_STRING );

        if( !isset( $json['generator'] ) )
            return false;

        return $json;
    }
}