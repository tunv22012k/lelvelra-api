<?php

namespace App\Repositories;

use App\Repositories\Interface\RepositoryInterface;

/**
 * BaseRepository
 */
abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    /**
     * __construct
     *
     *
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * getModel
     *
     * @return [type]
     *
     */
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    /**
     * Load relations
     *
     * @param  array  $relations
     * @return self
     */
    public function with($relations)
    {
        $this->model = $this->model->with($relations);

        return $this;
    }

    /**
     * get all data
     *
     * @return [type]
     *
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * find by id
     *
     * @param mixed $id
     *
     * @return [type]
     *
     */
    public function find($id)
    {
        $result = $this->model->find($id);

        return $result;
    }

    /**
     * create
     *
     * @param array $attributes
     *
     * @return [type]
     *
     */
    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }

    /**
     * update
     *
     * @param mixed $id
     * @param array $attributes
     *
     * @return [type]
     *
     */
    public function update($id, $attributes = [])
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }

    /**
     * delete
     *
     * @param mixed $id
     *
     * @return [type]
     *
     */
    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }

    /**
     * Get data of repository by pagination
     *
     * @param  array  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $limit = null, array $columns = ['*'])
    {
        $result = $this->model->paginate($limit, $columns);

        return $result;
    }
}
