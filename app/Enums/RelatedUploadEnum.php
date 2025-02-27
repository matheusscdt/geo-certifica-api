<?php

namespace App\Enums;

enum RelatedUploadEnum: string
{
    case perfil = 'perfil';
    case documentos = 'documentos';
    case assinaturas = 'assinaturas';
    case certificados = 'certificados';
    case registroAssinaturaInterna = 'registro-assinatura-interna';
}
