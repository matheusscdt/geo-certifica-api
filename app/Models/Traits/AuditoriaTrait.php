<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Support\Collection;

trait AuditoriaTrait
{
    public function castAuditoria(): Collection
    {
        return collect($this->castAuditoria);
    }

    public function serializeAuditoria(): Collection
    {
        return $this->audits->map(function ($audit) {
            return [
                'id' => $audit->id,
                'acao' => $audit->event,
                'user' => $audit->user,
                'user_id' => $audit->user_id,
                'data_acao' => $audit->created_at->toDateTimeString(),
                'campos' => collect($audit->new_values)->map(function ($value, $field) {
                    return [
                        'campo' => $field,
                        'valor' => $value
                    ];
                })->values()
            ];
        })->transform(function ($auditoria) {
            $fields = $this->castAuditoria()->pluck('field')->toArray();
            $auditoria['campos'] = collect($auditoria['campos'])->whereIn('campo', $fields);
            return $auditoria;
        });
    }

    private function booleanCast($type, $valor)
    {
        if ($type == 'boolean') {
            return $valor ? 'Sim' : 'Não';
        }

        return $valor;
    }

    private function moneyCast($type, $valor)
    {
        if ($type == 'money') {
            return getMoney($valor);
        }

        return $valor;
    }


    private function modelCast($type, $valor, $fieldType)
    {
        if (isModel($type)) {
            return $type::find($valor)?->{$fieldType} ?? 'Sem valor definido.';
        }

        return $valor;
    }

    private function dateCast($type, $valor)
    {
        if ($type == 'date') {
            return Carbon::parse($valor)->format('d/m/Y');
        }

        return $valor;
    }

    private function dateTimeCast($type, $valor)
    {
        if ($type == 'dateTime') {
            return Carbon::parse($valor)->format('d/m/Y H:i:s');
        }

        return $valor;
    }

    private function enumCast($type, $valor)
    {
        if (isEnum($type)) {
            return $type::from($valor)->label();
        }

        return $valor;
    }

    private function transformField($field): array
    {
        $campo = $this->castAuditoria()->where('field', $field['campo'])->first();
        $field['valor'] = $this->enumCast($campo['type'], $field['valor']);
        $field['valor'] = $this->booleanCast($campo['type'], $field['valor']);
        $field['valor'] = $this->dateCast($campo['type'], $field['valor']);
        $field['valor'] = $this->dateTimeCast($campo['type'], $field['valor']);
        $field['valor'] = $this->moneyCast($campo['type'], $field['valor']);
        $field['valor'] = $this->modelCast($campo['type'], $field['valor'], $campo['field_type'] ?? null);
        return $field;
    }

    private function labelField($field): array
    {
        $campo = $this->castAuditoria()->where('field', $field['campo'])->first();
        $field['campo'] = $campo['label'];
        return $field;
    }

    public function getAuditoria(): Collection
    {
        return $this->serializeAuditoria()->transform(function ($auditoria) {
            $auditoria['campos'] = $auditoria['campos']
                ->transform(fn($field) => $this->transformField($field))
                ->values()
                ->transform(fn($field) => $this->labelField($field));

            return $auditoria;
        })->filter(function ($auditoria) {
            if ($auditoria['acao'] == 'deleted') {
                return true;
            }

            return $auditoria['campos']->isNotEmpty();
        })->sortByDesc('data_acao');
    }
}
