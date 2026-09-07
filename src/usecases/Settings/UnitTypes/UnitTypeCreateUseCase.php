<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\FieldError;
use Vertuoza\Repositories\Settings\UnitTypes\DuplicateUnitTypeNameException;
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
				'TenantId is mandatory for unit type creation.'
			);
		}

		$name = trim($name);

		if ($name === '') {
			throw new BadUserInputException(
				new FieldError('name', 'Unit name cannot be empty.', 'EMPTY', 'input.name'),
				'UnitTypeCreateInput'
			);
		}

		if (mb_strlen($name, 'UTF-8') > 250) {
			throw new BadUserInputException(
				new FieldError('name', 'Unit name cannot exceed 250 characters.', 'MAX_LENGTH', 'input.name', ['max' => 250]),
				'UnitTypeCreateInput'
			);
		}

		$data = new UnitTypeMutationData();
		$data->name = $name;

		try {
			$id = $this->repository->create($data, $tenantId);
		} catch (DuplicateUnitTypeNameException $exception) {
			throw new BadUserInputException(
				new FieldError('name', $exception->getMessage(), 'ALREADY_EXISTS', 'input.name'),
				'UnitTypeCreateInput',
				$exception
			);
		}

		return $this->repository->getById($id, $tenantId);
	}
}
