<?php

use Carbon\Carbon;
use geekcom\ValidatorDocs\Rules\Cnpj;
use geekcom\ValidatorDocs\Rules\Cpf;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

if (!function_exists('getBaseUrlFrontEnd')) {
    function getBaseUrlFrontEnd()
    {
        return env('APP_URL_FRONT_END');
    }
}

if (!function_exists('getPerfilId')) {
    function getPerfilId(): ?string
    {
        return request()->header('perfil-id');
    }
}

if (!function_exists('getEmailTo')) {
    function getEmailTo()
    {
        return env('APP_ENV') != "prd" ? env('MAIL_TO_LICENCAS_DEV') : env('MAIL_TO_LICENCAS');
    }
}

if (!function_exists('getMoney')) {
    function getMoney($valor): string
    {
        return number_format($valor, 2, ',', '.');
    }
}

if (!function_exists('arrayOrderByAsc')) {
    function arrayOrderByAsc(array $array, string $field): array
    {
        usort($array, fn ($a, $b) => $a[$field] - $b[$field]);
        return $array;
    }
}

if (!function_exists('arrayOrderByDesc')) {
    function arrayOrderByDesc(array $array, string $field): array
    {
        usort($array, fn ($a, $b) => $b[$field] - $a[$field]);
        return $array;
    }
}

if (!function_exists('isEmail')){
    function isEmail($email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

if (!function_exists('isEnum')){
    function isEnum($class): bool {
        if (!class_exists($class)) {
            return false;
        }

        $reflection = new ReflectionClass($class);
        return $reflection->isSubclassOf(UnitEnum::class);
    }
}

if (!function_exists('isModel')){
    function isModel($class): bool {
        if (!class_exists($class)) {
            return false;
        }

        $reflection = new ReflectionClass($class);
        return $reflection->isSubclassOf(Illuminate\Database\Eloquent\Model::class);
    }
}

if (!function_exists('sanitizeCnpj')){
    function sanitizeCnpj(string $cnpj): string {
        return new Cnpj()->sanitize($cnpj);
    }
}

if (!function_exists('sanitizeCpf')){
    function sanitizeCpf(string $cpf): string {
        return new Cpf()->sanitize($cpf);
    }
}

if (!function_exists('sanitizeCpfCnpj')) {
    function sanitizeCpfCnpj(string $cpfCnpj): string
    {
        return preg_replace('/\D/', '', $cpfCnpj);
    }
}

if (!function_exists('gerarCodigo')) {
    function gerarCodigo(): int
    {
        return rand(1000, 9999);
    }
}

if (!function_exists('getSearchValue')) {
    function getSearchValue($field)
    {
        $search = request()->get('search');

        if (!is_null($search)) {
            $data = explode(':', $search);
            if ($data[0] == $field) {
                return $data[1];
            }
        }

        return null;
    }
}

if (!function_exists('convertTimestampToDate')) {
    function convertTimestampToDate(int $timestamp): Carbon
    {
        return Carbon::createFromTimestamp($timestamp);
    }
}

if (!function_exists('getHashSha256fromFileContent')) {
    function getHashSha256fromFileContent($fileContent): string
    {
        return hash('sha256', $fileContent);
    }
}

if (!function_exists('convertToCamelCase')) {
    function convertToCamelCase(string $text): string
    {
        return mb_convert_case($text, MB_CASE_TITLE, "UTF-8");
    }
}

if (!function_exists('formatDataHoraAtualCompleta')) {
    function formatDataHoraAtualCompleta(?Carbon $date = null): string
    {
        $date = $date ?? Carbon::now();
        return $date->locale('pt_BR')
                    ->timezone('America/Sao_Paulo')
                    ->isoFormat('[Gerado] dddd, D [de] MMMM [de] YYYY [às] HH:mm [(horário de Brasília)]');
    }
}

if (!function_exists('getDataHoraAtualCompleta')) {
    function getDataHoraAtualCompleta(): string
    {
        return formatDataHoraAtualCompleta();
    }
}

if (!function_exists('encryptFileBase64')) {
    function encryptFileBase64($contentFile): string
    {
        return Crypt::encryptString(base64_encode($contentFile));
    }
}

if (!function_exists('isValidUuid')) {
    function isValidUuid($uuid): bool
    {
        return Str::isUuid($uuid);
    }
}
