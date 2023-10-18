<?php

namespace App\Common;

use App\Common\Exceptions\ObjectNotFoundException;
use App\Common\Exceptions\RelationNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class EloquentRepository implements RepositoryInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model|\Eloquent
     */
    protected $_model;

    /**
     * EloquentRepository constructor.
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * get model
     * @return string
     */
    abstract public function getModel();

    /**
     * Set model
     * @throws BindingResolutionException
     */
    public function setModel()
    {
        try {
            $this->_model = app()->make(
                $this->getModel()
            );
        } catch (BindingResolutionException $e) {
            throw $e;
        }
    }

    /**
     * Get All
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->_model->all();
    }

    /**
     * get
     * @param WhereClause[] $whereClauses
     * @param string $orderBy
     * @param array $with
     * @param array $withCount
     * @return \Illuminate\Support\Collection
     */
    public function get($whereClauses, $orderBy = 'id:asc', $with = [], $withCount = [])
    {
        $query = $this->_model->newQuery();
        $this->createQuery($query, $whereClauses);
        $this->addOrderBy($query, $orderBy);
        if (!empty($with)) {
            $query = $query->with($with);
        }

        if (!empty($withCount)) {
            $query = $query->withCount($withCount);
        }
        return $query->get();
    }

    /**
     * paginate
     * @param int $limit
     * @param WhereClause[] $whereClauses
     * @param string $orderBy
     * @param array $with
     * @param array $withCount
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($limit, $whereClauses, $orderBy, $with = [], $withCount = [])
    {
        $query = $this->_model->newQuery();
        $this->createQuery($query, $whereClauses);
        $this->addOrderBy($query, $orderBy);

        if (!empty($with)) {
            $query = $query->with($with);
        }

        if (!empty($withCount)) {
            $query = $query->withCount($withCount);
        }

        return $query->paginate($limit);
    }

    /**
     * Find By Id
     * @param $id
     * @param array $with
     * @param array $withCount
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById($id, $with = [], $withCount = [])
    {
        $query = $this->_model;
        if (!empty($with)) {
            $query = $query->with($with);
        }

        if (!empty($withCount)) {
            $query = $query->withCount($withCount);
        }
        return $query->find($id);
    }


    /**
     * find
     * @param WhereClause[] $whereClauses
     * @param string $orderBy
     * @param array $with
     * @param array $withCount
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($whereClauses, $orderBy = null, $with = [], $withCount = [])
    {
        $query = $this->_model->newQuery();
        $this->createQuery($query, $whereClauses);
        $this->addOrderBy($query, $orderBy);

        if (!empty($with)) {
            $query = $query->with($with);
        }

        if (!empty($withCount)) {
            $query = $query->withCount($withCount);
        }

        return $query->first();
    }

    /**
     * Create
     * @param array $attributes
     * @param array $with
     * @param array $withCount
     * @return \Illuminate\Database\Eloquent\Model
     * @throws RelationNotFoundException
     */
    public function create(array $attributes, $with = [], $withCount = [])
    {
        if (isset($attributes['relations'])) {
            $relations = $attributes['relations'];
            unset($attributes['relations']);
        }
        $model = $this->_model->newQuery()->create($attributes);
        if (!empty($relations)) {
            foreach ($relations as $name => $elements) {
                if (empty($elements)) {
                    continue;
                }
                if ($model->isRelation($name)) {
                    $isList = true;
                    foreach ($elements as $index => $element) {
                        if (!is_int($index)) {
                            $isList = false;
                            break;
                        }
                    }
                    if (!$isList) {
                        $model->{$name}()->create($elements);
                    } else {
                        foreach ($elements as $element) {
                            $model->{$name}()->create($element);
                        }
                    }
                } else {
                    throw new RelationNotFoundException();
                }
            }
        }

        if ($model) {
            if (!empty($with)) {
                $model->load($with);
            }

            if (!empty($withCount)) {
                $model->loadCount($withCount);
            }
            return $model;
        } else {
            return null;
        }
    }

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @param array $with
     * @param array $withCount
     * @return bool|\Illuminate\Database\Eloquent\Model
     * @throws ObjectNotFoundException
     */
    public function update($id, array $attributes, $with = [], $withCount = [])
    {
        $model = $id;
        if (is_scalar($id)) {
            $model = $this->findById($id);
        }
        if ($model) {
            if ($model->update($attributes)) {
                if (!empty($with)) {
                    $model->load($with);
                }

                if (!empty($withCount)) {
                    $model->loadCount($withCount);
                }
                return $model;
            } else {
                return null;
            }
        } else {
            throw new ObjectNotFoundException();
        }
    }

    /**
     * Delete
     *
     * @param $id
     * @param array $with
     * @return bool
     * @throws ObjectNotFoundException
     * @throws RelationNotFoundException
     */
    public function delete($id, $with = [])
    {
        $model = $id;
        if (is_scalar($id)) {
            $model = $this->findById($id);
        }
        if ($model) {
            if (!empty($with)) {
                foreach ($with as $relation) {
                    if ($model->isRelation($relation)) {
                        $model->{$relation}()->delete();
                    } else {
                        throw new RelationNotFoundException();
                    }
                }
            }
            if ($model->delete()) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new ObjectNotFoundException();
        }
    }

    /**
     * Create or Update
     * @param $id
     * @param array $attributes
     * @param array $with
     * @param array $withCount
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createOrUpdate($id, array $attributes, $with = [], $withCount = [])
    {
        $model = $id;
        if (is_array($id)) {
            $model = $this->_model->newQuery()->updateOrCreate($id, $attributes);
            if ($model) {
                if (!empty($with)) {
                    $model->load($with);
                }
                if (!empty($withCount)) {
                    $model->loadCount($withCount);
                }
                return $model;
            }
            return null;
        } else if (is_numeric($id)) {
            $model = $this->findById($id);
        }

        if ($model) {
            if ($model->update($attributes)) {
                if (!empty($with)) {
                    $model->load($with);
                }
                if (!empty($withCount)) {
                    $model->loadCount($withCount);
                }
                return $model;
            } else {
                return null;
            }
        } else {
            return $this->create($attributes, $with, $withCount);
        }
    }

    public function bulkUpdate(array $whereClauses, array $attributes)
    {
        $query = $this->_model->newQuery();
        $this->createQuery($query, $whereClauses);
        return $query->update($attributes);
    }


    public function bulkDelete(array $whereClauses)
    {
        $query = $this->_model->newQuery();
        $this->createQuery($query, $whereClauses);
        return $query->delete();
    }


    /**
     * @param Builder $query
     * @param WhereClause[] $whereClauses
     */
    private function createQuery(&$query, $whereClauses)
    {
        if (isset($whereClauses)) {
            foreach ($whereClauses as $clause) {
                if ($clause->getOperator() == 'raw') {
                    $query = $query->whereRaw($clause->getColumn());
                } else if ($clause->getOperator() == 'has') {
                    if ($clause->getFunction()) {
                        $query = $query->whereHas($clause->getRelation(), $clause->getFunction(), $clause->getRelationOperator(), $clause->getRelationCount());
                    } else {
                        $query = $query->has($clause->getRelation(), $clause->getRelationOperator(), $clause->getRelationCount());
                    }
                } else if ($clause->getOperator() == 'function') {
                    $query = $query->where($clause->getFunction());
                } else if ($clause->getOperator() == 'or') {
                    $orClauses = $clause->getValue();
                    $query = $query->where(function (Builder $q) use ($orClauses) {
                        $q->where($this->createQueryOr($q, $orClauses));
                    });
                } else if ($clause->getOperator() == 'whereHas') {
                    $orClauses = $clause->getValue();
                    $query = $query->whereHas($clause->getRelation(), function (Builder $q) use ($orClauses) {
                        $q->where($this->createQueryOr($q, $orClauses));
                    }, $clause->getRelationOperator(), $clause->getRelationCount());
                } else if (Str::startsWith($clause->getOperator(), 'fn_')) {
                    $query = $this->whereByName($query, $clause);
                } else {
                    $query = $query->where($clause->getColumn(), $clause->getOperator(), $clause->getValue());
                }
            }
        }
    }

    /**
     * @param Builder $query
     * @param WhereClause $clause
     * @return Builder|mixed
     */
    private function whereByName($query, $clause)
    {
        if (!Str::startsWith($clause->getOperator(), 'fn_')) {
            return $query;
        }

        list($fn, $name, $operator) = explode('_', $clause->getOperator());
        $name = Str::ucfirst($name);
        $functionName = "where$name";
        return $query->{$functionName}($clause->getColumn(), $operator, $clause->getValue());
    }

    /**
     * @param Builder $query
     * @param string $orderBy
     */
    private function addOrderBy(&$query, $orderBy)
    {
        if (!empty($orderBy)) {
            $orderFields = preg_split('/\,/', $orderBy);
            foreach ($orderFields as $field) {
                list($column, $value) = preg_split('/:/', $field);
                $query = $query->orderBy($column, $value);
            }
        }
    }

    /**
     * @param Builder $query
     * @param WhereClause[] $whereClauses
     */
    private function createQueryOr(&$query, $whereClauses)
    {
        if (isset($whereClauses)) {
            foreach ($whereClauses as $clause) {
                if ($clause->getOperator() == 'raw') {
                    $query = $query->orWhereRaw($clause->getColumn());
                } else if ($clause->getOperator() == 'has') {
                    if ($clause->getFunction()) {
                        $query = $query->orWhereHas($clause->getRelation(), $clause->getFunction(), $clause->getRelationOperator(), $clause->getRelationCount());
                    } else {
                        $query = $query->orHas($clause->getRelation(), $clause->getRelationOperator(), $clause->getRelationCount());
                    }
                } else if ($clause->getOperator() == 'function') {
                    $query = $query->orWhere($clause->getFunction());
                } else if ($clause->getOperator() == 'or') {
                    $orClauses = $clause->getValue();
                    $query = $query->orWhere(function (Builder $q) use ($orClauses) {
                        $q->where($this->createQuery($q, $orClauses));
                    });
                } else if ($clause->getOperator() == 'whereHas') {
                    $orClauses = $clause->getValue();
                    $query = $query->orWhereHas($clause->getRelation(), function (Builder $q) use ($orClauses) {
                        $q->where($this->createQueryOr($q, $orClauses));
                    }, $clause->getRelationOperator(), $clause->getRelationCount());
                } else if (Str::startsWith($clause->getOperator(), 'fn_')) {
                    $query = $this->whereByName($query, $clause);
                } else {
                    $query = $query->orWhere($clause->getColumn(), $clause->getOperator(), $clause->getValue());
                }
            }
        }
    }

    public function truncate()
    {
        $this->_model->newQuery()->truncate();
    }


}
