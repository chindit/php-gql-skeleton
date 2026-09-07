<?php

namespace Vertuoza\Repositories\Collaborators;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use function React\Promise\resolve;
use Vertuoza\Repositories\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Collaborators\Models\CollaboratorModel;
use Vertuoza\Repositories\Database\QueryBuilder;

class CollaboratorRepository
{
	/** @var array<string, DataLoader> */
	private array $loaders = [];
	public function __construct(
		private QueryBuilder $database,
		private PromiseAdapterInterface $promiseAdapter
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

	public function findById(string $id, string $tenantId): Promise
	{
		if (!isset($this->loaders[$tenantId])) {
			$this->loaders[$tenantId] = new DataLoader(function (array $ids) use ($tenantId) {
				$rows = $this->database->getConnection()
					->table(CollaboratorModel::getTableName())
					->where(CollaboratorModel::getTenantColumnName(), $tenantId)
					->whereNull('deleted_at')
					->whereIn(CollaboratorModel::getPkColumnName(), $ids)
					->get();

				$entities = [];
				foreach ($rows as $row) {
					$entity = CollaboratorMapper::modelToEntity(CollaboratorModel::fromStdclass($row));
					$entities[$entity->id] = $entity;
				}

				// DataLoader requires one result per key, in the same order, including missing keys.
				return resolve(array_map(fn ($id) => $entities[$id] ?? null, $ids));
			}, $this->promiseAdapter);
		}

		return $this->loaders[$tenantId]->load($id);
	}
}
