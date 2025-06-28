<?php

namespace App\Observers;

use App\Models\Common\LogAudits;
use App\Exceptions\NotTitleDefined;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;

class BaseObserver
{
    protected $title;
    protected $details;

    public function __construct()
    {
        $this->title = $this->resolveTitle();
        $this->details = $this->resolveDetails();
    }

    /**
     * Handle the RewardsGroups "created" event.
     */
    public function created(Model $entity): void
    {
        $user = auth()->user();

        if ($user != null) {
            $users_name = $user->name . " " . $user->last_name;
            LogAudits::create([
                "route" => "/" . Route::getFacadeRoot()->current()->uri(),
                "title" => $this->title,
                "action" => "created",
                "method" => Route::getFacadeRoot()->current()->methods()[0],
                "users_id" => $user->id,
                "table_name" => $entity->getTable(),
                "users_name" => $users_name,
                "users_email" => $user->email,
                "table_item_id" => isset($entity->getAttributes()["id"]) ? $entity->getAttributes()["id"] : null,
                "what_heppened" => $entity->getAttributes(),
                "description" => '"' . $users_name . '" created a new in "' . $this->title . '"',
                "ip" => request()->ip(),
            ]);
        }
    }

    /**
     * Handle the RewardsGroups "updated" event.
     */
    public function updated(Model $entity): void
    {
        $user = auth()->user();

        //The logout "route" always updates the user table's remember_token, so we should disregard this case.
        if ($user != null and Route::currentRouteName() != "logout" and Route::currentRouteName() != "login.in") {
            $users_name = $user->name . " " . $user->last_name;
            $old = [];

            foreach ($entity->getDirty() as $dirtyKey => $dirtyValue) {
                $old[$dirtyKey] = $entity->getOriginal($dirtyKey);
            }

            //Novas alterações
            $dirty = $entity->getDirty();

            //Desconsiderar a alteração feita na coluna updated_at
            if (isset($dirty["updated_at"])) {
                unset($dirty["updated_at"]);
            }

            if (isset($old["updated_at"])) {
                unset($old["updated_at"]);
            }

            LogAudits::create([
                "route" => "/" . Route::getFacadeRoot()->current()->uri(),
                "title" => $this->title,
                "action" => "updated",
                "method" => Route::getFacadeRoot()->current()->methods()[0],
                "users_id" => $user->id,
                "table_name" => $entity->getTable(),
                "users_name" => $users_name,
                "users_email" => $user->email,
                "table_item_id" => isset($entity->getAttributes()["id"]) ? $entity->getAttributes()["id"] : null,
                "what_heppened" => [
                    "id" => $entity->id,
                    "old" => $old,
                    "new" => $dirty,
                    "details" => $this->details != null ? $this->details : "",
                ],
                "description" => '"' . $users_name . '" updated the information in "' . $this->title . '"',
                "ip" => request()->ip(),
            ]);
        }
    }

    /**
     * Handle the RewardsGroups "deleted" event.
     */
    public function deleted(Model $entity): void
    {
        $user = auth()->user();

        if ($user != null) {
            $users_name = $user->name . " " . $user->last_name;
            LogAudits::create([
                "route" => "/" . Route::getFacadeRoot()->current()->uri(),
                "title" => $this->title,
                "action" => "deleted",
                "method" => Route::getFacadeRoot()->current()->methods()[0],
                "users_id" => $user->id,
                "table_name" => $entity->getTable(),
                "users_name" => $users_name,
                "users_email" => $user->email,
                "table_item_id" => isset($entity->getAttributes()["id"]) ? $entity->getAttributes()["id"] : null,
                "what_heppened" => $entity->getAttributes(),
                "description" => '"' . $users_name . '" permanently deleted in "' . $this->title . '"',
                "ip" => request()->ip(),
            ]);
        }
    }

    public function resolveTitle()
    {
        if (!method_exists($this, "title")) {
            throw new NotTitleDefined();
        }

        return $this->title();
    }

    public function resolveDetails()
    {
        if (!method_exists($this, "details")) {
            return null;
        }

        return $this->details();
    }
}
