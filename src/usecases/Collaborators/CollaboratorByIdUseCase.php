<?php

namespace Vertuoza\Usecases\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Collaborators\CollaboratorEntity;
use Vertuoza\Libs\Exceptions\NotFoundException;
use Vertuoza\Repositories\Collaborators\CollaboratorRepository;

class CollaboratorByIdUseCase
{
	public function __construct(
		private CollaboratorRepository $repository,
		private UserRequestContext $userContext
	) {
	}

	public function handle(string $id): CollaboratorEntity
	{
		$tenantId = $this->userContext->getTenantId();

		if ($tenantId === null) {
			throw new \RuntimeException(
				'TenantId is mandatory for any collaborator query'
			);
		}

		$collaborator = $this->repository->findById($id, $tenantId);

		if ($collaborator === null) {
			throw new NotFoundException('This collaborator does not exist.');
		}

		return $collaborator;
	}
}
