<?php

namespace App\Repositories;

use App\Repositories\Interface\RepositoryInterface;

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
}
