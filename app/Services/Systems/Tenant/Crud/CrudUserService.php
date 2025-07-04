<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Libs\Enums\EnumStatus;
use App\Libs\Enums\EnumOrderBy;
use App\Services\BaseCrudService;
use App\Libs\Enums\Systems\Tenant\EnumTenantRoles;
use App\Repositories\Systems\Tenant\UserRepository;

class CrudUserService extends BaseCrudService
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    // /**
    //  * Verifica o nível de permissão do usuário e retorna para ele todos os usuários que ele tem permissão
    //  * de visualizar, editar ou excluir
    //  */

    public function getUsersByLevel($user, $columnName = null, $order = EnumOrderBy::ASC)
    {
        if ($user->roles->id == EnumTenantRoles::ADMIN) {
            return $this->repository->getAll([], [$columnName => $order]);
        }
        if ($user->roles->id == EnumTenantRoles::OWNER) {
            return $this->repository->getAll(["roles_id" => EnumTenantRoles::CLIENT], [$columnName => $order]);
        }
    }

    /**
     * Para atualizar um usuário, precisamos verificar se ele está mudando a senha.
     * Caso contrário, apenas salvar as demais informações excluindo o password.
     */
    public function updateUser($id, $request)
    {
        $user = $this->repository->findById($id);
        if (!empty($request->password)) {
            return $this->repository->update($user->id, $request->all());
        } else {
            return $this->repository->saveExceptionPassword($user, $request);
        }
    }
}
