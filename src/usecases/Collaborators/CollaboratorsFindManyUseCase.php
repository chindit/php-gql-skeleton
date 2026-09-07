<?php

namespace Vertuoza\Usecases\Collaborators;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Collaborators\CollaboratorRepository;

readonly class CollaboratorsFindManyUseCase
{
	public function __construct(
		private CollaboratorRepository $repository,
		private UserRequestContext $userContext
	) {
	}

	public function handle(): Promise
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
