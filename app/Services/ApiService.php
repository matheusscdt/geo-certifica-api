<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use League\Fractal\Manager;
use League\Fractal\Scope;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\LaravelValidator;

abstract class ApiService
{
    protected abstract function repository(): RepositoryInterface;

    protected function relations(): array
    {
        return [];
    }

    protected abstract function presenter(): FractalPresenter;

    protected abstract function validator(): ?LaravelValidator;

    public function findAll()
    {
        $limit = request()->get('limit') ?? config('repository.pagination.limit');
        $withTrashed = request()->get('withTrashed', null) == true ?? false;
        $onlyTrashed = request()->get('onlyTrashed', null) == true ?? false;

        return $this->repository()->scopeQuery(function ($query) use ($withTrashed, $onlyTrashed) {
            if ($withTrashed) {
                return $query->withTrashed();
            }

            if ($onlyTrashed) {
                return $query->onlyTrashed();
            }

            return $query;
        })->with($this->relations())->setPresenter($this->presenter())->paginate($limit);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, ValidatorInterface::RULE_CREATE);
        return $this->create($data);
    }

    protected function validate(Request $request, ?string $validator = null): array
    {
        $data = $this->getRulesValidated($request, $validator);
        $this->validator()->with($data)->passesOrFail($validator);
        return $data;
    }

    public function validateWithValidator(Request $request, string $validator, LaravelValidator $laravelValidator): array
    {
        $rules = array_keys($laravelValidator->getRules($validator));
        $data = $request->only($rules);
        $laravelValidator->with($data)->passesOrFail($validator);
        return $data;
    }

    protected function getRulesValidated(Request $request, string $method, LaravelValidator $validator = null): array
    {
        $rules = array_keys($this->validator()->getRules($method));

        if ($validator !== null) {
            $rules = array_keys($validator->getRules($method));
        }

        return $request->only($rules);
    }

    public function create(array $data)
    {
        $result = $this->repository()->create($data);
        return $result->refresh();
    }

    public function updateOrCreate(array $data)
    {
        $result = $this->repository()->updateOrCreate($data['attributes'], $data['values']);
        return $result->refresh();
    }

    public function createMany(array $data): Collection
    {
        $result = collect();
        foreach ($data as $value) {
            $result->push($this->create($value));
        }

        return $result;
    }

    public function updateOrCreateMany(array $data): Collection
    {
        $result = collect();
        foreach ($data as $value) {
            $result->push($this->updateOrCreate($value));
        }

        return $result;
    }

    public function findByField($field, $value)
    {
        return $this->repository()->findByField($field, $value)->first();
    }

    public function find($id)
    {
        return $this->repository()->find($id);
    }

    public function findById($id)
    {
        $onlyTrashed = request()->get('onlyTrashed', null) == true ?? false;
        $withTrashed = request()->get('withTrashed', null) == true ?? false;

        return $this->repository()->scopeQuery(function ($query) use ($withTrashed, $onlyTrashed) {

            if ($withTrashed) {
                return $query->withTrashed();
            }

            if ($onlyTrashed) {
                return $query->onlyTrashed();
            }

            return $query;
        })->with($this->relations())->setPresenter($this->presenter())->find($id);
    }

    public function edit(array $data, $id)
    {
        $certificado = $this->repository()->update($data, $id);
        return $certificado->refresh();
    }

    public function remove($id): int
    {
        return $this->repository()->delete($id);
    }

    public function update(Request $request, $id)
    {
        $data = $this->getRulesValidated($request, ValidatorInterface::RULE_UPDATE);
        $this->validator()->setId($id)->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        return $this->repository()->update($data, $id)->refresh();
    }

    public function delete($id): Response
    {
        $this->repository()->delete($id);
        return response()->noContent();
    }

    public function getCollectionTransform(Collection $data, $transformer): Scope
    {
        $fractal = new Manager();
        $collection = $transformer->collection($data, $transformer, true);
        return $fractal->createData($collection);
    }
}
