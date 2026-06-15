<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('Empresa')->count() === 0) {
            DB::table('Empresa')->insert([
                'empresa'              => 'Nombre de tu empresa',
                'slogan'               => '',
                'sigla'                => '',
                'telefono'             => '',
                'celular'              => '',
                'email'                => '',
                'direccion'            => '',
                'responsable'          => '',
                'latitud'              => '',
                'longitud'             => '',
                'objeto'               => '',
                'mision'               => '',
                'vision'               => '',
                'facebook'             => '',
                'instagram'            => '',
                'tiktok'               => '',
                'linkedin'             => '',
                'carrito'              => 'inactivo',
                'tipo_cambio'          => 0,

                'logo_cuadrado'        => '',
                'logo_largo'           => '',
                'baner_inicio'         => '',
                'icono'                => '',
                'titulo_cierre'        => '',
                'mensaje_cierre'       => '',
                'titulo_inicio'        => '',
                'mensaje_inicio'       => '',
                'dominio'              => '',
                'smtp_correo'          => '',
                'correo_institucional' => '',
                'pwd_institucional'    => '',
            ]);
        }
    }
}