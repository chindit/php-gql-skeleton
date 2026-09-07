<?php

namespace Vertuoza\Api\Graphql\Resolvers\Collaborators;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Types;

final class Collaborator extends ObjectType
{
	public function __construct()
	{
		parent::__construct([
			'name' => 'Collaborator',
			'fields' => [
				'id' => [
					'type' => new NonNull(Types::id()),
				],
				'name' => [
					'type' => new NonNull(Types::string()),
				],
				'firstName' => [
					'type' => new NonNull(Types::string()),
				],
			],
		]);
	}
}
