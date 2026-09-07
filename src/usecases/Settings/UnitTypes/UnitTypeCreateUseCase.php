<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Libs\Exceptions\BadRequestException;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class UnitTypeCreateUseCase
{
	public function __construct(
		private UnitTypeRepository $repository,
		private UserRequestContext $userContext
	) {
	}

	public function handle(string $name): Promise
	{
		$tenantId = $this->userContext->getTenantId();

		if ($tenantId === null) {
			throw new \RuntimeException(
				'TenantId is mandatory for any collaborator query.'
			);
		}

		$name = trim($name);

		if (empty($name) || strlen($name) > 250) {
			throw new BadRequestException(
				'Unit name must be between 1 and 250 characters long.'
			);
		}

		$data = new UnitTypeMutationData();
		$data->name = $name;

		$id = $this->repository->create($data, $tenantId);

		return $this->repository->getById($id, $tenantId);
	}
}
