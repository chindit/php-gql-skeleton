<?php

namespace Vertuoza\Repositories\Collaborators;

use Vertuoza\Entities\Collaborators\CollaboratorEntity;
use Vertuoza\Repositories\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Collaborators\Models\CollaboratorModel;
use Vertuoza\Repositories\Database\QueryBuilder;

class CollaboratorRepository
{
	public function __construct(
		private QueryBuilder $database
	) {
	}

	public function findMany(string $tenantId): array
	{
		$rows = $this->database
			->getConnection()
			->table(CollaboratorModel::getTableName())
			->where(CollaboratorModel::getTenantColumnName(), $tenantId)
			->whereNull('deleted_at')
			->get();

		$collaborators = [];

		foreach ($rows as $row) {
			$collaborators[] = CollaboratorMapper::modelToEntity(
				CollaboratorModel::fromStdclass($row)
			);
		}

		return $collaborators;
	}

	public function findById(string $id, string $tenantId): ?CollaboratorEntity
	{
		$row = $this->database
			->getConnection()
			->table(CollaboratorModel::getTableName())
			->where(CollaboratorModel::getPkColumnName(), $id)
			->where(CollaboratorModel::getTenantColumnName(), $tenantId)
			->whereNull('deleted_at')
			->first();

		if (!$row) {
			return null;
		}

		return CollaboratorMapper::modelToEntity(
			CollaboratorModel::fromStdclass($row)
		);
	}
}
