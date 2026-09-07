<?php

namespace Vertuoza\Repositories\Collaborators;

use Vertuoza\Entities\Collaborators\CollaboratorEntity;
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
			->table('collaborator')
			->where('tenant_id', $tenantId)
			->whereNull('deleted_at')
			->get();

		$collaborators = [];

		foreach ($rows as $row) {
			$collaborator = new CollaboratorEntity();
			$collaborator->id = $row->id;
			$collaborator->name = $row->name;
			$collaborator->firstName = $row->first_name;

			$collaborators[] = $collaborator;
		}

		return $collaborators;
	}

	public function findById(string $id, string $tenantId): ?CollaboratorEntity
	{
		$row = $this->database
			->getConnection()
			->table('collaborator')
			->where('id', $id)
			->where('tenant_id', $tenantId)
			->whereNull('deleted_at')
			->first();

		if (!$row) {
			return null;
		}

		$collaborator = new CollaboratorEntity();
		$collaborator->id = $row->id;
		$collaborator->name = $row->name;
		$collaborator->firstName = $row->first_name;

		return $collaborator;
	}
}
