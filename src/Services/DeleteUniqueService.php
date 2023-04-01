<?php

namespace Waad\DUSD\Services;

class DeleteUniqueService
{
    private $model;
    private $modelClass;
    private $uniuqeAttributes;


    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     * @return self
     */
    public function setModel($model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }

    /**
     * @param mixed $modelQuery
     * @return self
     */
    public function setModelClass($modelClass): self
    {
        $this->modelClass = $modelClass;
        return $this;
    }

    /**
     * @return array
     */
    public function getUniuqeAttributes()
    {
        return $this->uniuqeAttributes;
    }

    /**
     * @param array $UniuqeAttributes
     * @return self
     */
    public function setUniuqeAttributes(): self
    {
        $this->uniuqeAttributes = $this->getModel()->unique_attributes;
        return $this;
    }


    /**
     * check Unique Attributes is Exist
     *
     * @return bool
     */
    public function checkUniqueAttributesExist()
    {
        if (property_exists($this->getModel(), 'unique_attributes')) {
            return true;
        }

        return false;
    }

    /**
     * check Unique Attributes is Array
     *
     * @return bool
     */
    public function checkUniqueAttributesArray()
    {
        if (is_array($this->getUniuqeAttributes())) {
            return true;
        }

        return false;
    }


    /**
     * search And Force Delete
     *
     * @return void
     */
    public function searchAndForceDelete()
    {
        $modelQuery = $this->getModelClass();

        foreach ($this->getUniqueSelectAttributes() as $attr) {

            if ($this->checkUniqueOrOperator()) {
                $modelQuery = $modelQuery->orWhere($attr, $this->getModel()->$attr);
            } else {
                $modelQuery = $modelQuery->where($attr, $this->getModel()->$attr);
            }
        }

        if ($modelQuery->exists() && property_exists($this->getModel(), 'forceDeleting')) {
            abort_if(!$modelQuery->forceDelete(), 404, 'One of the unique values still exists');
        }
    }

    /**
     * check Unique (Or) Operator
     *
     * @return bool
     */
    private function checkUniqueOrOperator()
    {
        if (
            request()->has('unique_or_operator') && (request()->get('unique_or_operator') == 1 ||
                request()->get('unique_or_operator') == true
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * get Unique Select Attributes
     *
     * @return array
     */
    private function getUniqueSelectAttributes()
    {
        $key = 'unique_select_attributes';
        if (request()->exists($key) && request()->has($key)) {
            return explode(',', trim(request()->get($key)));
        } else {
            return $this->getUniuqeAttributes();
        }
    }

    public function checkCreatedExist()
    {
        $modelQuery = $this->getModelClass();
        $modelQuery = $modelQuery->where('id', '!=', $this->getModel()->id);
        $modelQuery = $modelQuery->where(function ($query) {
            foreach ($this->getUniqueSelectAttributes() as $attr) {
                $query = $query->orWhere($attr, $this->getModel()->$attr);
            }
            return $query;
        });

        // foreach ($this->getUniuqeAttributes() as $attr) {
        //     $modelQuery = $modelQuery->orWhere($attr, $this->getModel()->$attr);
        // }

        abort_if($modelQuery->exists(), 404, 'One of the unique values still exists');
    }
}
