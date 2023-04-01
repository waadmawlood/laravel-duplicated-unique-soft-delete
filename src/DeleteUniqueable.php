<?php

namespace Waad\DUSD;

use Waad\DUSD\Services\DeleteUniqueService;

trait DeleteUniqueable
{
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $deleteUniqueService = new DeleteUniqueService();
            $deleteUniqueService->setModel($model);

            if (!$deleteUniqueService->checkUniqueAttributesExist()) {
                return;
            }

            $deleteUniqueService->setUniuqeAttributes();


            if (!$deleteUniqueService->checkUniqueAttributesArray()) {
                return;
            }

            $deleteUniqueService->setModelClass(self::query());
            $deleteUniqueService->searchAndForceDelete();
        });

        self::created(function ($model) {
            $deleteUniqueService = new DeleteUniqueService();
            $deleteUniqueService->setModel($model);

            if(!$deleteUniqueService->checkUniqueAttributesExist()){
                return;
            }

            $deleteUniqueService->setUniuqeAttributes();


            if (!$deleteUniqueService->checkUniqueAttributesArray()) {
                return;
            }

            $deleteUniqueService->setModelClass(self::query());
            $deleteUniqueService->checkCreatedExist();
        });
    }
}
