<?php

namespace Vertuoza\Usecases\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Collaborators\CollaboratorRepository;

class CollaboratorsFindManyUseCase
{
	public function __construct(
		private CollaboratorRepository $repository,
		private UserRequestContext $userContext
	) {
	}

	public function handle(): array
	{
		$tenantId = $this->userContext->getTenantId();

		if ($tenantId === null) {
			throw new \RuntimeException(
				'TenantId is mandatory for any collaborator query'
			);
		}

		return $this->repository->findMany($tenantId);
	}
}
